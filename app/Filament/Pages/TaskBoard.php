<?php

namespace App\Filament\Pages;

use App\Models\Task;

use Filament\Schemas\Components\Grid;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Relaticle\Flowforge\Board;
use Relaticle\Flowforge\BoardPage;
use Relaticle\Flowforge\Column;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\{TextEntry};
use Filament\Forms\Components\{Textarea, Select, DatePicker};
use App\Models\{User, Employee};


use Filament\Actions\{EditAction, DeleteAction, CreateAction, ViewAction};
use Filament\Forms\Components\TextInput;

class TaskBoard extends BoardPage
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-view-columns';
    protected static ?string $navigationLabel = 'Task Board';
    protected static ?string $title = 'Task Board';

    protected static string|\UnitEnum|null $navigationGroup = "Work space";

    public function board(Board $board): Board
    {
        return $board
            ->searchable(['title', 'description'])

            ->query($this->getEloquentQuery())
            ->recordTitleAttribute('title')
            ->columnIdentifier('status')
            ->positionIdentifier('position')
            ->cardSchema(fn(Schema $schema) => $schema->components([
                TextEntry::make('email')
                    ->icon('heroicon-o-user')
                    ->hiddenLabel()
                    ->tooltip('Assigned to')
                ,
                TextEntry::make('description')
                    ->hiddenLabel()
                    ->limit(50, end: ' ...')
                    ->tooltip('Description'),
                TextEntry::make('due_date')
                    ->date()
                    ->icon('heroicon-o-calendar')
                    ->hiddenLabel()
                    ->badge()
                    ->tooltip('Due date')
            ]))
            ->cardActions([
                ViewAction::make()
                    ->model(Task::class)
                    ->schema([
                        TextEntry::make('title')
                            ->weight(FontWeight::Bold)
                            ->hiddenLabel()
                            ->hint('Title')
                        ,
                        TextEntry::make('description')
                            ->hint('Description')
                            ->hiddenLabel()
                        ,
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('email')
                                    ->hiddenLabel()
                                    ->hint('Assigned to')
                                    ->icon('heroicon-o-user')
                                    ->copyable()
                                ,
                                TextEntry::make('due_date')
                                    ->hiddenLabel()
                                    ->hint('Due date')
                                    ->icon('heroicon-o-calendar-days')
                            ])

                    ])
                ,
                EditAction::make()
                    ->model(Task::class)
                    ->form([
                        TextInput::make('title')->required(),
                        Textarea::make('description'),
                        Grid::make()
                            ->columns(3)
                            ->schema([
                                Select::make('assignee_id')
                                    ->required()
                                    ->afterStateHydrated(function (Select $component, $state) {
                                        if ($record = $component->getRecord()) {
                                            $assigneeType = $record->assignee_type;
                                            $assigneeId = $record->assignee_id;

                                            if ($assigneeType && $assigneeId) {
                                                $prefix = $assigneeType === Employee::class ? 'Employee_' : 'User_';
                                                $component->state($prefix . $assigneeId);
                                            }
                                        }
                                    })
                                    ->label('Assignee')
                                    ->options(
                                        collect()
                                            ->merge(
                                                Employee::all()->mapWithKeys(
                                                    fn($employee) => [
                                                        "Employee_" . $employee->id =>
                                                            $employee->email
                                                    ],
                                                ),
                                            )
                                            ->merge(
                                                User::all()->mapWithKeys(
                                                    fn($user) => [
                                                        "User_" . $user->id =>
                                                            $user->email
                                                    ],
                                                ),
                                            ),
                                    )
                                ,
                                Select::make('status')
                                    ->options([
                                        'todo' => 'Todo',
                                        'in_progress' => 'In progress',
                                        'completed' => 'Completed'
                                    ])
                                ,
                                DatePicker::make('due_date')
                                    ->label('Due Date'),
                            ])
                    ])
                    ->mutateFormDataUsing(function (array $data, array $arguments): array {
                        $assigneeId = $data['assignee_id'];
                        $assigneeType = null;
                        $parsedAssigneeId = null;

                        if (str_starts_with($assigneeId, 'Employee_')) {
                            $parsedAssigneeId = str_replace('Employee_', '', $assigneeId);
                            $assigneeType = Employee::class;
                        } elseif (str_starts_with($assigneeId, 'User_')) {
                            $parsedAssigneeId = str_replace('User_', '', $assigneeId);
                            $assigneeType = User::class;
                        }

                        $data['assignee_id'] = $parsedAssigneeId;
                        $data['assignee_type'] = $assigneeType;
                        return $data;
                    })
                ,
                DeleteAction::make()->model(Task::class),
            ])->cardAction('view')
            ->columns([
                Column::make('todo')->label('To Do')->color('gray'),
                Column::make('in_progress')->label('In Progress')->color('blue'),
                Column::make('completed')->label('Completed')->color('green'),
            ])
            ->columnActions([
                CreateAction::make()
                    ->label(' ')
                    ->iconButton()->icon('heroicon-o-plus')
                    ->model(Task::class)
                    ->form([
                        TextInput::make('title')->required(),
                        Textarea::make('description'),
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                Select::make('assignee_id')
                                    ->label('Assignee')
                                    ->options(
                                        collect()
                                            ->merge(
                                                Employee::all()->mapWithKeys(
                                                    fn($employee) => [
                                                        "Employee_" . $employee->id =>
                                                            $employee->email
                                                    ],
                                                ),
                                            )
                                            ->merge(
                                                User::all()->mapWithKeys(
                                                    fn($user) => [
                                                        "User_" . $user->id =>
                                                            $user->email
                                                    ],
                                                ),
                                            ),
                                    )
                                ,
                                DatePicker::make('due_date')
                                    ->label('Due Date'),
                            ])
                    ])
                    ->mutateFormDataUsing(function (array $data, array $arguments) {
                        $status = $arguments['column'];

                        // Handle assignee_id parsing (employee_1 or user_1)
                        $assigneeId = $data['assignee_id'];
                        $assigneeType = null;

                        if (str_starts_with($assigneeId, 'Employee_')) {
                            $assigneeId = str_replace('Employee_', '', $assigneeId);
                            $assigneeType = Employee::class;
                        } elseif (str_starts_with($assigneeId, 'User_')) {
                            $assigneeId = str_replace('User_', '', $assigneeId);
                            $assigneeType = User::class;
                        }
                        $data['assignee_id'] = $assigneeId;
                        $data['assignee_type'] = $assigneeType;
                        $data['status'] = $arguments['column'] ?? $data['status'] ?? null;
                        $data['position'] = $this->getBoardPositionInColumn($arguments['column']);
                        return $data;
                    })

            ])
        ;


    }

    public function getEloquentQuery(): Builder
    {
        return Task::query()->with('assignee');
    }
}
