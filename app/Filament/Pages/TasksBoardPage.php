<?php

namespace App\Filament\Pages;

use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Relaticle\Flowforge\Filament\Pages\KanbanBoardPage;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\DatePicker;

use App\Models\Employee;

class TasksBoardPage extends KanbanBoardPage
{
    protected static ?string $navigationIcon = 'heroicon-o-view-columns';
    protected static ?string $navigationLabel = 'Tasks Board Page';
    protected static ?string $title = 'Task Board';
    protected static ?string $navigationGroup = 'Project Management';

    public function getSubject(): Builder
    {
        return Task::query();
    }

    public function mount(): void
    {
        $this
            ->titleField('title')
            ->orderField('sort_order')
            ->columnField('status')
            ->descriptionField('description')
            ->cardAttributes([
                'employee.full_name' => '',
                'date' => '',
            ])
            ->cardAttributeColors([
                'employee.full_name' => 'white',
                'due_date' => 'gray',
            ])

            ->cardAttributeIcons([
                'employee.full_name' => 'heroicon-o-user',
                'date' => 'heroicon-o-calendar',
            ])
            ->columns([
                'todo' => 'To Do',
                'in_progress' => 'In Progress',
                'completed' => 'Completed',
            ])
            ->columnColors([
                'todo' => 'sky',
                'in_progress' => 'yellow',
                'completed' => 'green',
            ]);
    }
    public function createAction(Action $action): Action
    {
        return $action
            ->iconButton()
            ->icon('heroicon-o-plus')
            ->modalHeading('Create Task')
            ->modalWidth('xl')
            ->form(function (Forms\Form $form) {
                return $form->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->placeholder('Enter task title')
                        ->columnSpanFull(),
                    Grid::make()
                        ->columns(2)
                        ->schema([
                            Select::make('employee_id')
                                ->options(function () {
                                    return Employee::all()->pluck('full_name', 'id');
                                })
                                ->searchable(
                                    [
                                        'first_name',
                                        'last_name',
                                    ]
                                )

                                ->label('Employee'),
                            DatePicker::make('due_date')
                                ->label('Due Date'),
                        ]),
                    Forms\Components\Textarea::make('description')
                        ->columnSpanFull(),
                    // Add more form fields as needed
                ]);
            });
    }
    public function editAction(Action $action): Action
    {
        return $action
            ->modalHeading('Edit Task')
            ->modalWidth('xl')
            ->form(function (Forms\Form $form) {
                return $form->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->placeholder('Enter task title')
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('description')
                        ->columnSpanFull(),
                    Grid::make()
                        ->columns(2)
                        ->schema([
                            Select::make('employee_id')
                                ->options(function () {
                                    return Employee::all()->pluck('full_name', 'id');
                                })
                                ->searchable(
                                    [
                                        'first_name',
                                        'last_name',
                                    ]
                                )

                                ->label('Employee'),
                            DatePicker::make('due_date')
                                ->label('Due Date'),
                        ]),
                    Forms\Components\Select::make('status')
                        ->options([
                            'todo' => 'To Do',
                            'in_progress' => 'In Progress',
                            'completed' => 'Completed',
                        ])
                        ->required(),
                    // Add more form fields as needed
                ]);
            });
    }

}
