<!DOCTYPE html>
<html>

<head>
    <title>Employee Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .summary {
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Employee Organization Report</h1>
        <p>Total Organizations: {{ $result['total_organizations'] }}</p>
        <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    @foreach($result['report'] as $organization)
        <div class="summary">
            <h2>{{ $organization['organization_name'] }}</h2>
            <p>Industry: {{ $organization['industry'] }}</p>
            <p>Location: {{ $organization['location'] }}</p>
            <p>Total Employees: {{ $organization['total_employees'] }}</p>
            <p>Average Salary: ${{ number_format($organization['average_salary'] ?? 0, 2) }}</p>
        </div>

        @foreach($organization['teams'] as $team)
            <table>
                <thead>
                    <tr>
                        <th colspan="6">{{ $team['team_name'] }} Team</th>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Position</th>
                        <th>Salary</th>
                        <th>Start Date</th>
                        <th>Tenure (Years)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($team['employees'] as $employee)
                        <tr>
                            <td>{{ $employee['name'] }}</td>
                            <td>{{ $employee['email'] }}</td>
                            <td>{{ $employee['position'] }}</td>
                            <td>${{ number_format($employee['salary'], 2) }}</td>
                            <td>{{ $employee['start_date'] }}</td>
                            <td>{{ number_format($employee['tenure_years'], 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center;">No employees in this team</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endforeach
    @endforeach
</body>

</html>