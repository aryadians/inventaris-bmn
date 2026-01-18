<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Room;
use App\Models\Asset;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;

class StockOpname extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Stock Opname';
    protected static string $view = 'filament.pages.stock-opname';

    public ?int $selectedRoomId = null;
    public $assets = [];
    public array $assetStates = [];

    public bool $opnameCompleted = false;
    public array $report = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('selectedRoomId')
                ->label('Pilih Ruangan untuk Stock Opname')
                ->options(Room::pluck('nama_ruangan', 'id'))
                ->reactive()
                ->afterStateUpdated(function ($state) {
                    $this->opnameCompleted = false;
                    $this->report = [];
                    $this->loadAssets();
                }),
        ];
    }
    
    public function loadAssets()
    {
        if ($this->selectedRoomId) {
            $this->assets = Asset::where('room_id', $this->selectedRoomId)->get();
            $this->assetStates = [];
            foreach ($this->assets as $asset) {
                $this->assetStates[$asset->id] = [
                    'found' => true, // Default to found
                    'new_condition' => $asset->kondisi,
                ];
            }
        } else {
            $this->assets = [];
            $this->assetStates = [];
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Hasil Stock Opname')
                ->action('saveOpname')
                ->visible(count($this->assets) > 0 && !$this->opnameCompleted),
        ];
    }

    public function saveOpname()
    {
        $this->report = ['matched' => 0, 'condition_changed' => 0, 'not_found' => 0];

        foreach ($this->assets as $asset) {
            $state = $this->assetStates[$asset->id];

            if (!$state['found']) {
                $this->report['not_found']++;
                $asset->catatan = ($asset->catatan ? $asset->catatan . "\n" : '') . "Tidak ditemukan saat stock opname " . now()->toDateString();
                $asset->save();
                continue;
            }

            if ($asset->kondisi !== $state['new_condition']) {
                $this->report['condition_changed']++;
                $asset->kondisi = $state['new_condition'];
                $asset->catatan = ($asset->catatan ? $asset->catatan . "\n" : '') . "Kondisi diubah menjadi {$state['new_condition']} saat stock opname " . now()->toDateString();
                $asset->save();
            } else {
                $this->report['matched']++;
            }
        }

        $this->opnameCompleted = true;
        Notification::make()
            ->title('Stock Opname Selesai')
            ->success()
            ->send();
    }
}
