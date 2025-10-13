<?php

// namespace App\Filament\Pages;

// use Filament\Schemas\Schema;
// use Filament\Forms\Components\TextInput;
// use Filament\Schemas\Components\Grid;
// use Filament\Forms\Components\Textarea;
// use App\Models\Task;
// use Illuminate\Database\Eloquent\Builder;
// use Relaticle\Flowforge\Filament\Pages\KanbanBoardPage;
// use Filament\Actions\Action;
// use Filament\Forms;
// use Filament\Forms\Components\Select;
// use Filament\Forms\Components\DatePicker;

// use App\Models\Employee;

// class TasksBoardPage extends KanbanBoardPage
// {
//     protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-view-columns';
//     protected static ?string $navigationLabel = 'Tasks Board Page';
//     protected static ?string $title = 'Task Board';
//     protected static string | \UnitEnum | null $navigationGroup = 'Work space';

//     public function getSubject(): Builder
//     {
//         return Task::query();
//     }

//     public function mount(): void
//     {
//         $this
//             ->titleField('title')
//             ->orderField('sort_order')
//             ->columnField('status')
//             ->descriptionField('description')
//             ->cardLabel('Task')
//             ->pluralCardLabel('Tasks')
//             ->cardAttributes([
//                 'employee.email' => '',
//                 'date' => '',
//             ])
//             ->cardAttributeColors([
//                 'employee.email' => 'white',
//                 'due_date' => 'gray',
//             ])

//             ->cardAttributeIcons([
//                 'employee.email' => 'heroicon-o-user',
//                 'date' => 'heroicon-o-calendar',
//             ])
//             ->columns([
//                 'todo' => 'To Do',
//                 'in_progress' => 'In Progress',
//                 'completed' => 'Completed',
//             ])
//             ->columnColors([
//                 'todo' => 'sky',
//                 'in_progress' => 'yellow',
//                 'completed' => 'green',
//             ]);
//     }
//     public function createAction(Action $action): Action
//     {
//         return $action
//             ->iconButton()
//             ->icon('heroicon-o-plus')
//             ->modalHeading('Create Task')
//             ->modalWidth('xl')
//             ->schema(function (Schema $schema) {
//                 return $schema->components([
//                     TextInput::make('title')
//                         ->required()
//                         ->placeholder('Enter task title')
//                         ->columnSpanFull(),
//                     Grid::make()
//                         ->columns(2)
//                         ->schema([
//                             Select::make('employee_id')
//                                 ->options(function () {
//                                     return Employee::all()->pluck('email', 'id');
//                                 })
//                                 ->searchable(
//                                     [
//                                         'first_name',
//                                         'last_name',
//                                         'email'
//                                     ]
//                                 )

//                                 ->label('Assigned to'),
//                             DatePicker::make('due_date')
//                                 ->label('Due Date'),
//                         ]),
//                     Textarea::make('description')
//                         ->columnSpanFull(),
//                     // Add more form fields as needed
//                 ]);
//             });
//     }
//     public function editAction(Action $action): Action
//     {
//         return $action
//             ->modalHeading('Edit Task')
//             ->modalWidth('xl')
//             ->schema(function (Schema $schema) {
//                 return $schema->components([
//                     TextInput::make('title')
//                         ->required()
//                         ->placeholder('Enter task title')
//                         ->columnSpanFull(),
//                     Textarea::make('description')
//                         ->columnSpanFull(),
//                     Grid::make()
//                         ->columns(2)
//                         ->schema([
//                             Select::make('employee_id')
//                                 ->options(function () {
//                                     return Employee::all()->pluck('email', 'id');
//                                 })
//                                 ->searchable(
//                                     [
//                                         'first_name',
//                                         'last_name',
//                                         'email'
//                                     ]
//                                 )

//                                 ->label('Assigned to'),
//                             DatePicker::make('due_date')
//                                 ->label('Due Date'),
//                         ]),
//                     Select::make('status')
//                         ->options([
//                             'todo' => 'To Do',
//                             'in_progress' => 'In Progress',
//                             'completed' => 'Completed',
//                         ])
//                         ->required(),
//                     // Add more form fields as needed
//                 ]);
//             });
//     }

// }
