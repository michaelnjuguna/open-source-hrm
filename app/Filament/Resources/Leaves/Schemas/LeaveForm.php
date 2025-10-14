<?php
namespace App\Filament\Resources\Leaves\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\{Select, DatePicker, TextArea};
use App\Models\{Employee};
class LeaveForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //

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
                    ->required()
                    ->label('Employee'),
                Select::make('leave_type')
                    ->options([
                        'Sick Leave' => 'Sick Leave',
                        'Vacation' => 'Vacation',
                        'Personal Leave' => 'Personal Leave',
                        'Maternity Leave' => 'Maternity Leave',
                        'Paternity Leave' => 'Paternity Leave',
                        'Bereavement Leave' => 'Bereavement Leave',
                        'Other' => 'Other',
                    ])
                    ->required(),
                DatePicker::make('start_date')
                    ->required()
                    ->label('Start Date'),
                DatePicker::make('end_date')
                    ->required()
                    ->label('End Date'),
                Select::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Approved' => 'Approved',
                        'Rejected' => 'Rejected',
                    ])
                    ->default('Pending')
                    ->required(),
                Textarea::make('rejection_reason')
                    ->nullable()
                    ->columnSpan('full')
                    ->label('Rejection Reason'),
                Textarea::make('notes')
                    ->nullable()
                    ->columnSpan('full')
                    ->label('Notes'),


            ]);
    }
}