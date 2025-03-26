<?php
function generateSampleEmployeeData($count = 1000, $outputFile = 'sample_employee_data.json')
{
    $teamIds = \App\Models\Team::pluck('id')->toArray();
    $organizationIds = \App\Models\Organization::pluck('id')->toArray();

    if (empty($teamIds) || empty($organizationIds)) {
        return ['status' => false, "message" => " No teams or organizations found in the database. Please create some first"];
    }

    $firstNames = [
        'James',
        'John',
        'Robert',
        'Michael',
        'William',
        'David',
        'Richard',
        'Joseph',
        'Thomas',
        'Charles',
        'Mary',
        'Patricia',
        'Jennifer',
        'Linda',
        'Elizabeth',
        'Barbara',
        'Susan',
        'Jessica',
        'Sarah',
        'Karen',
        'Emma',
        'Olivia',
        'Noah',
        'Liam',
        'Mason',
        'Jacob',
        'William',
        'Ethan',
        'Michael',
        'Alexander',
        'Sophia',
        'Isabella',
        'Charlotte',
        'Mia',
        'Amelia',
        'Harper',
        'Evelyn',
        'Abigail',
        'Emily',
        'Elizabeth'
    ];

    $lastNames = [
        'Smith',
        'Johnson',
        'Williams',
        'Jones',
        'Brown',
        'Davis',
        'Miller',
        'Wilson',
        'Moore',
        'Taylor',
        'Anderson',
        'Thomas',
        'Jackson',
        'White',
        'Harris',
        'Martin',
        'Thompson',
        'Garcia',
        'Martinez',
        'Robinson',
        'Clark',
        'Rodriguez',
        'Lewis',
        'Lee',
        'Walker',
        'Hall',
        'Allen',
        'Young',
        'Hernandez',
        'King',
        'Wright',
        'Lopez',
        'Hill',
        'Scott',
        'Green',
        'Adams',
        'Baker',
        'Gonzalez',
        'Nelson',
        'Carter'
    ];


    $positions = [
        'Software Engineer',
        'Senior Software Engineer',
        'Principal Engineer',
        'QA Engineer',
        'DevOps Engineer',
        'Product Manager',
        'Project Manager',
        'UX Designer',
        'UI Designer',
        'Data Scientist',
        'Data Analyst',
        'Marketing Specialist',
        'Sales Representative',
        'Customer Support Representative',
        'HR Specialist',
        'Financial Analyst',
        'Accounting Manager',
        'Operations Manager',
        'Administrative Assistant',
        'Executive Assistant'
    ];

    // Salary ranges
    $salarySets = [
        [40000, 60000],
        [60000, 90000],
        [90000, 130000],
        [130000, 180000],
        [180000, 250000]
    ];


    $employees = [];
    $domains = ['example.com', 'company.org', 'enterprise.co', 'corp.net', 'business.io'];

    $startTime = time();
    $batchSize = 50; // Process in batches to avoid memory issues

    for ($i = 0; $i < $count; $i++) {
        $firstName  = $firstNames[array_rand($firstNames)];
        $lastName   = $lastNames[array_rand($lastNames)];
        $name       = $firstName . ' ' . $lastName;

        //  email (with some randomization to avoid duplicates)
        $emailPrefix = strtolower($firstName . '.' . $lastName);
        $emailPrefix = preg_replace('/[^a-z.]/', '', $emailPrefix);

        if ($i > count($firstNames) * count($lastNames)) {
            $emailPrefix .= rand(1, 999);
        }
        $domain = $domains[array_rand($domains)];
        $email = $emailPrefix . '@' . $domain;

        // Random start date between 5 years ago and today
        $startDate = date('Y-m-d', strtotime('-' . rand(0, 1825) . ' days'));

        //  appropriate salary range based on position index
        $positionIndex = array_rand($positions);
        $salaryRange = $salarySets[min(4, intdiv($positionIndex, 4))];
        $salary = rand($salaryRange[0], $salaryRange[1]);

        $employees[] = [
            'name' => $name,
            'email' => $email,
            'team_id' => $teamIds[array_rand($teamIds)],
            'organization_id' => $organizationIds[array_rand($organizationIds)],
            'salary' => $salary,
            'start_date' => $startDate,
            'position' => $positions[$positionIndex]
        ];
    }

    // Check if directory exists
    $directory = dirname($outputFile);
    if ($directory !== '.' && !is_dir($directory)) {
        mkdir($directory, 0755, true);
    }

    // Save to JSON file
    $jsonData = json_encode($employees, JSON_PRETTY_PRINT);
    file_put_contents($outputFile, $jsonData);

    $fileSize = filesize($outputFile) / (1024 * 1024); // in MB


    return ['status' => true, 'message' => "Successfully generated $count employee records"];
}
