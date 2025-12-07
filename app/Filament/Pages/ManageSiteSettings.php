<?php

namespace App\Filament\Pages;

use App\Models\SiteSettings;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageSiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Paramètres';

    protected static ?string $navigationLabel = 'Personnalisation';

    protected static ?string $title = 'Personnalisation du site';

    protected static ?int $navigationSort = 99;

    protected string $view = 'filament.pages.manage-site-settings';

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(): void
    {
        $settings = SiteSettings::instance();

        $this->form->fill([
            'site_name' => $settings->site_name,
            'logo_path' => $settings->logo_path,
            'locale' => $settings->locale ?? 'fr',
            'consultant_primary_color' => $settings->consultant_primary_color,
            'consultant_secondary_color' => $settings->consultant_secondary_color,
            'consultant_accent_color' => $settings->consultant_accent_color,
            'commercial_primary_color' => $settings->commercial_primary_color,
            'commercial_secondary_color' => $settings->commercial_secondary_color,
            'commercial_accent_color' => $settings->commercial_accent_color,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identité visuelle')
                    ->description('Configurez le nom et le logo de votre entreprise')
                    ->schema([
                        TextInput::make('site_name')
                            ->label('Nom du site')
                            ->required()
                            ->maxLength(255),
                        FileUpload::make('logo_path')
                            ->label('Logo de l\'entreprise')
                            ->image()
                            ->disk('public')
                            ->directory('branding')
                            ->imagePreviewHeight('100')
                            ->maxSize(2048)
                            ->helperText('Format recommandé : PNG ou SVG, max 2 Mo'),
                    ]),

                Section::make('Langue de l\'interface')
                    ->description('Configurez la langue par défaut de l\'application')
                    ->schema([
                        Select::make('locale')
                            ->label('Langue')
                            ->options(SiteSettings::availableLocales())
                            ->required()
                            ->native(false)
                            ->helperText('Cette langue sera utilisée pour toute l\'interface et les emails'),
                    ]),

                Section::make('Thème Consultant')
                    ->description('Couleurs pour l\'interface des consultants (par défaut : bleu)')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                ColorPicker::make('consultant_primary_color')
                                    ->label('Couleur principale')
                                    ->required(),
                                ColorPicker::make('consultant_secondary_color')
                                    ->label('Couleur secondaire')
                                    ->required(),
                                ColorPicker::make('consultant_accent_color')
                                    ->label('Couleur accent')
                                    ->required(),
                            ]),
                    ]),

                Section::make('Thème Commercial')
                    ->description('Couleurs pour l\'interface des commerciaux (par défaut : orange)')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                ColorPicker::make('commercial_primary_color')
                                    ->label('Couleur principale')
                                    ->required(),
                                ColorPicker::make('commercial_secondary_color')
                                    ->label('Couleur secondaire')
                                    ->required(),
                                ColorPicker::make('commercial_accent_color')
                                    ->label('Couleur accent')
                                    ->required(),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $settings = SiteSettings::instance();
        $settings->update($data);

        Notification::make()
            ->title('Paramètres enregistrés')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Enregistrer')
                ->submit('save'),
            Action::make('reset')
                ->label('Réinitialiser les couleurs')
                ->color('gray')
                ->action(function () {
                    $this->form->fill([
                        'site_name' => $this->data['site_name'] ?? 'Mission Dashboard',
                        'logo_path' => $this->data['logo_path'] ?? null,
                        'consultant_primary_color' => '#3B82F6',
                        'consultant_secondary_color' => '#1E40AF',
                        'consultant_accent_color' => '#60A5FA',
                        'commercial_primary_color' => '#F97316',
                        'commercial_secondary_color' => '#C2410C',
                        'commercial_accent_color' => '#FB923C',
                    ]);

                    Notification::make()
                        ->title('Couleurs réinitialisées')
                        ->info()
                        ->send();
                }),
        ];
    }
}
