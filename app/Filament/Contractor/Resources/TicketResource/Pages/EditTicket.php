<?php

namespace App\Filament\Contractor\Resources\TicketResource\Pages;

use App\Enums\TicketImageType;
use App\Enums\TicketStatus;
use App\Filament\Contractor\Resources\TicketResource;
use App\Models\Property;
use App\Models\Ticket;
use App\Models\TicketImage;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $this->createImagesModel(
            $data['images'],
            TicketImageType::IMAGE
        );

        $this->createSingleImageModel(
            $this->signatureToImage($data['signature']),
            TicketImageType::SIGNATURE
        );

        $record->update([
            'status' => TicketStatus::REVIEW,
            'resolution_at' => now(),
        ]);

        return $record;
    }

    public function createImagesModel(array $imagePaths, TicketImageType $type): void
    {
        foreach ($imagePaths as $path) {
            $this->createSingleImageModel($path, $type);
        }
    }

    public function createSingleImageModel(string $imagePath, TicketImageType $type): void
    {
        /** @var Ticket $ticket */
        $ticket = $this->getRecord();

        TicketImage::create([
            'ticket_id' => $ticket->id,
            'path' => $imagePath,
            'type' => $type
        ]);
    }

    public function signatureToImage(string $data_uri): string
    {
        $encoded_image = explode(",", $data_uri)[1];
        $decoded_image = base64_decode($encoded_image);

        /** @var Ticket $ticket */
        $ticket = $this->getRecord();

        $folderPath = 'signatures';
        $filePath = "$folderPath/{$ticket->id}/signature.png";

        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        Storage::disk('public')
            ->put($filePath, $decoded_image);

        return $filePath;
    }

}
