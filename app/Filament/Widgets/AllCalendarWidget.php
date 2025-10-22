<?php

namespace App\Filament\Widgets;
use App\Models\Event;
use \Guava\Calendar\Filament\CalendarWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Guava\Calendar\ValueObjects\{FetchInfo, DateClickInfo};
use Guava\Calendar\ValueObjects\CalendarEvent;
use Guava\Calendar\Filament\Actions\{CreateAction, EditAction, DeleteAction};
use Filament\Forms\Components\{TextInput, Select, Textarea, Checkbox, DateTimePicker, Toggle};
use Filament\Schemas\Components\{Grid};
class AllCalendarWidget extends CalendarWidget
{
    protected static ?string $title = 'Calendar';
    protected static ?int $sort = 2;
    protected bool $eventDragEnabled = true;
    protected bool $dateClickEnabled = true;
    protected bool $eventClickEnabled = true;


    public function createEventAction(): CreateAction
    {
        return $this->createAction(Event::class)
            ->label('New')
            ->icon('heroicon-o-plus')
            ->schema([
                TextInput::make('title')
                    ->required(),
                Textarea::make('description'),
                Grid::make(2)
                    ->schema([
                        Select::make('type')
                            ->options([
                                'meeting' => 'Meeting',
                                'appointment' => 'Appointment',
                                'deadline' => 'Deadline',
                                'event' => 'Event'
                            ]),
                        Toggle::make('all_day')
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state) {

                                if ($state) {
                                    $set('start_time', now()->startOfDay());
                                    $set('end_time', now()->endOfDay());

                                }
                            })

                            ->label('All day'),

                    ]),
                Grid::make(2)
                    ->schema([
                        DateTimePicker::make('start_time')
                            ->required()
                            ->readOnly(fn($get) => $get('all_day'))

                        ,
                        DateTimePicker::make('end_time')
                            ->required()
                            ->readOnly(fn($get) => $get('all_day'))

                    ])


            ])
        ;
    }
    public function editEventAction(): EditAction
    {
        return $this->editAction(Event::class)
            ->label('Edit')
            ->icon('heroicon-o-pencil')
            ->schema([
                TextInput::make('title')
                    ->required(),
                Textarea::make('description'),
                Grid::make(2)
                    ->schema([
                        Select::make('type')
                            ->options([
                                'meeting' => 'Meeting',
                                'appointment' => 'Appointment',
                                'deadline' => 'Deadline',
                                'event' => 'Event'
                            ]),
                        Toggle::make('all_day')
                            ->inlineLabel(false)
                            ->label('All day'),

                    ]),
                Grid::make(2)
                    ->schema([
                        DateTimePicker::make('start_time')
                            ->required()
                        ,
                        DateTimePicker::make('end_time')
                            ->required()
                    ])


            ])
        ;
    }

    public function deleteEventAction(): DeleteAction
    {
        return DeleteAction::make('deleteEvent')
            ->model(Event::class);

    }

    public function getHeaderActions(): array
    {
        return [
            $this->createEventAction(),


        ];
    }

    protected function getDateClickContextMenuActions(): array
    {
        return [
            $this->createEventAction(),

        ];
    }

    protected function getEventClickContextMenuActions(): array
    {
        return [
            $this->viewAction(),
            $this->editEventAction(),
            $this->deleteEventAction(),
        ];
    }

    // protected string $view = 'filament.widgets.calendar-widget';
    public function getEvents(FetchInfo $info): array|Collection|Builder
    {
        $start = $info->start;
        $end = $info->end;
        return Event::query()
            ->where(function ($query) use ($start, $end) {
                $query
                    ->orWhereBetween('end_time', [$start, $end])
                    ->whereBetween('start_time', [$start, $end]);

            });
    }
}
