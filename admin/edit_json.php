<?php
// Load existing JSON data
$jsonFile = '../assets/json/commendations.json';
$jsonData = [];

if (file_exists($jsonFile)) {
    $jsonContent = file_get_contents($jsonFile);
    $jsonData = json_decode($jsonContent, true);
    
    // Check if jsonData is an array
    if (!is_array($jsonData)) {
        $jsonData = []; // Fallback to empty array if data is not an array
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit JSON File</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
        }
        /* Styles for the collapsible section */
        .section {
            margin-bottom: 20px;
            margin: 10px 10px 20px 10px;
            border-radius: 5px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
        }

        .section h3 {
            margin: 0;
            cursor: pointer;
            user-select: none; /* Prevent text selection on click */
        }

        .section h2 {
            margin-top: 0;
        }

        .section-content {
            display: none;
            padding-top: 10px;
        }

        /* Optional: Style when section is open */
        .section.open .section-content {
            display: block;
        }

        .input-group {
            margin-bottom: 10px;
        }
        .input-group label {
            display: block;
            margin-bottom: 5px;
        }
        .input-group input, .input-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .input-group textarea {
            resize: vertical;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-bottom: 20px;
            font-weight: bold;
            color: #007bff;
        }
        .repeater {
            margin-top: 10px;
        }
        .repeater .section {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }
        .time-limited-section {
            display: none;
            margin-top: 10px;
        }
        .time-limited-section.active {
            display: block;
        }
    </style>
    <script>
        function toggleTimeLimitedFields(index) {
            const timeLimitedCheckbox = document.getElementById(`timeLimited${index}`);
            const timeLimitedSection = document.getElementById(`timeLimitedSection${index}`);
            if (timeLimitedCheckbox.checked) {
                timeLimitedSection.classList.add('active');
            } else {
                timeLimitedSection.classList.remove('active');
            }
        }

        function addCommendation() {
            const repeater = document.getElementById('commendationsRepeater');
            const index = commendationCount++;
            const gradeIndex = 0;
            const rewardIndex = 0;

            const newCommendation = document.createElement('div');
            newCommendation.className = 'section closed';
            newCommendation.innerHTML = `
                <h3>Commendation ${index + 1}</h3>
                <div class="section-content">
                    <div class="input-group">
                        <label for="commendations[${index}][name]">Name:</label>
                        <input type="text" id="commendations[${index}][name]" name="commendations[${index}][name]" required>
                    </div>
                    <div class="input-group">
                        <label for="commendations[${index}][description]">Description:</label>
                        <textarea id="commendations[${index}][description]" name="commendations[${index}][description]" required></textarea>
                    </div>
                    <div class="input-group">
                        <label for="commendations[${index}][section]">Section:</label>
                        <input type="text" id="commendations[${index}][section]" name="commendations[${index}][section]">
                    </div>
                    <div class="input-group">
                        <label for="commendations[${index}][subSection]">Sub Section:</label>
                        <input type="text" id="commendations[${index}][subSection]" name="commendations[${index}][subSection]">
                    </div>
                    <div class="input-group">
                        <label for="commendations[${index}][image]">Image Path:</label>
                        <input type="text" id="commendations[${index}][image]" name="commendations[${index}][image]">
                    </div>
                    <div class="input-group">
                        <label for="timeLimited${index}">Time Limited:</label>
                        <input type="checkbox" id="timeLimited${index}" name="commendations[${index}][timeLimited]" value="true" onclick="toggleTimeLimitedFields(${index})">
                    </div>
                    <div id="timeLimitedSection${index}" class="time-limited-section">
                        <div class="input-group">
                            <label for="commendations[${index}][timeLimitedStart]">Start Date:</label>
                            <input type="date" id="commendations[${index}][timeLimitedStart]" name="commendations[${index}][timeLimitedStart]">
                        </div>
                        <div class="input-group">
                            <label for="commendations[${index}][timeLimitedEnd]">End Date:</label>
                            <input type="date" id="commendations[${index}][timeLimitedEnd]" name="commendations[${index}][timeLimitedEnd]">
                        </div>
                    </div>
                    <div class="input-group">
                        <label>Grades:</label>
                        <div class="repeater" id="gradesRepeater${index}">
                            <!-- Grades will be added dynamically here -->
                        </div>
                        <button type="button" onclick="addGrade(${index})">Add Grade</button>
                    </div>
                    <div class="input-group">
                        <label>Rewards:</label>
                        <div class="repeater" id="rewardsRepeater${index}">
                         <!-- Rewards will be added dynamically here -->
                        </div>
                        <button type="button" onclick="addReward(${index})">Add Reward</button>
                    </div>
                </div>
            `;
            repeater.appendChild(newCommendation);
            gradeCount[index] = 0;
            rewardCount[index] = 0;
        }

        function addGrade(commIndex) {
            const repeater = document.getElementById(`gradesRepeater${commIndex}`);
            const index = gradeCount[commIndex]++;
            const newGrade = document.createElement('div');
            newGrade.className = 'section';
            newGrade.innerHTML = `
                <h4>Grade ${index + 1}</h4>
                <div class="input-group">
                    <label for="commendations[${commIndex}][grades][${index}][name]">Name:</label>
                    <input type="text" id="commendations[${commIndex}][grades][${index}][name]" name="commendations[${commIndex}][grades][${index}][name]" value="Grade ${index + 1}">
                </div>
                <div class="input-group">
                    <label for="commendations[${commIndex}][grades][${index}][description]">Description:</label>
                    <textarea id="commendations[${commIndex}][grades][${index}][description]" name="commendations[${commIndex}][grades][${index}][description]"></textarea>
                </div>
            `;
            repeater.appendChild(newGrade);
        }

        function addReward(commIndex) {
            const repeater = document.getElementById(`rewardsRepeater${commIndex}`);
            const index = rewardCount[commIndex]++;
            const newReward = document.createElement('div');
            newReward.className = 'section';
            newReward.innerHTML = `
                <h4>Reward ${index + 1}</h4>
                <div class="input-group">
                    <label for="commendations[${commIndex}][rewards][${index}][type]">Type:</label>
                    <input type="text" id="commendations[${commIndex}][rewards][${index}][type]" name="commendations[${commIndex}][rewards][${index}][type]">
                </div>
                <div class="input-group">
                    <label for="commendations[${commIndex}][rewards][${index}][description]">Description:</label>
                    <input type="text" id="commendations[${commIndex}][rewards][${index}][description]" name="commendations[${commIndex}][rewards][${index}][description]">
                </div>
            `;
            repeater.appendChild(newReward);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // const sections = document.querySelectorAll('.section h3');
            // sections.forEach(section => {
            //     console.log(section);
            //     section.addEventListener('click', function() {
            //         this.parentElement.classList.toggle('open');
            //     });
            // });

            // Initial commendation count
            window.commendationCount = <?php echo count($jsonData['commendations'] ?? []); ?>;
            // Initialize gradeCount and rewardCount
            window.gradeCount = {};
            window.rewardCount = {};
            
            const commendations = <?php echo json_encode($jsonData['commendations'] ?? []); ?>;
            commendations.forEach((commendation, index) => {
                gradeCount[index] = commendation.grades ? commendation.grades.length : 0;
                rewardCount[index] = commendation.rewards ? commendation.rewards.length : 1; // Initialize to 1 for the first reward field
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            // Function to toggle section
            function toggleSection(event) {
                const header = event.target.closest('h3');
                if (!header) return;

                const section = header.parentElement;
                if (section.classList.contains('closed')) {
                    section.classList.remove('closed');
                    section.classList.add('open');
                } else {
                    section.classList.remove('open');
                    section.classList.add('closed');
                }
            }

            // Attach event listener to the document for dynamic content
            document.addEventListener('click', toggleSection);
        });

    </script>
</head>
<body>
    <h1>Edit JSON File</h1>
    <form action="save_json.php" method="POST">
        <div id="commendationsRepeater" class="repeater">
            <?php if (!empty($jsonData['commendations'])): ?>
                <?php foreach ($jsonData['commendations'] as $index => $commendation): ?>
                    <div class="section closed">
                        <h3>Commendation <?php echo $index + 1; ?> - <?php echo htmlspecialchars($commendation['name']); ?></h3>
                        <div class="section-content">
                            <div class="input-group">
                                <label for="commendations[<?php echo $index; ?>][name]">Name:</label>
                                <input type="text" id="commendations[<?php echo $index; ?>][name]" name="commendations[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($commendation['name']); ?>" required>
                            </div>
                            <div class="input-group">
                                <label for="commendations[<?php echo $index; ?>][description]">Description:</label>
                                <textarea id="commendations[<?php echo $index; ?>][description]" name="commendations[<?php echo $index; ?>][description]" required><?php echo htmlspecialchars($commendation['description']); ?></textarea>
                            </div>
                            <div class="input-group">
                                <label for="commendations[<?php echo $index; ?>][section]">Section:</label>
                                <input type="text" id="commendations[<?php echo $index; ?>][section]" name="commendations[<?php echo $index; ?>][section]" value="<?php echo htmlspecialchars($commendation['section']); ?>">
                            </div>
                            <div class="input-group">
                                <label for="commendations[<?php echo $index; ?>][subSection]">Sub Section:</label>
                                <input type="text" id="commendations[<?php echo $index; ?>][subSection]" name="commendations[<?php echo $index; ?>][subSection]" value="<?php echo isset($commendation['subSection']) ? htmlspecialchars($commendation['subSection']) : ''; ?>">
                            </div>
                            <div class="input-group">
                                <label for="commendations[<?php echo $index; ?>][image]">Image Path:</label>
                                <input type="text" id="commendations[<?php echo $index; ?>][image]" name="commendations[<?php echo $index; ?>][image]" value="<?php echo htmlspecialchars($commendation['image']); ?>">
                            </div>
                            <div class="input-group">
                                <label for="timeLimited<?php echo $index; ?>">Time Limited:</label>
                                <input type="checkbox" id="timeLimited<?php echo $index; ?>" name="commendations[<?php echo $index; ?>][timeLimited]" value="true" <?php echo isset($commendation['timeLimited']) && $commendation['timeLimited'] ? 'checked' : ''; ?> onclick="toggleTimeLimitedFields(<?php echo $index; ?>)">
                            </div>
                            <div id="timeLimitedSection<?php echo $index; ?>" class="time-limited-section <?php echo isset($commendation['timeLimited']) && $commendation['timeLimited'] ? 'active' : ''; ?>">
                                <div class="input-group">
                                    <label for="commendations[<?php echo $index; ?>][timeLimitedStart]">Start Date:</label>
                                    <input type="date" id="commendations[<?php echo $index; ?>][timeLimitedStart]" name="commendations[<?php echo $index; ?>][timeLimitedStart]" value="<?php echo isset($commendation['timeLimitedStart']) ? $commendation['timeLimitedStart'] : ''; ?>">
                                </div>
                                <div class="input-group">
                                    <label for="commendations[<?php echo $index; ?>][timeLimitedEnd]">End Date:</label>
                                    <input type="date" id="commendations[<?php echo $index; ?>][timeLimitedEnd]" name="commendations[<?php echo $index; ?>][timeLimitedEnd]" value="<?php echo isset($commendation['timeLimitedEnd']) ? $commendation['timeLimitedEnd'] : ''; ?>">
                                </div>
                            </div>
                            <div class="input-group">
                                <label>Grades:</label>
                                <div class="repeater" id="gradesRepeater<?php echo $index; ?>">
                                    <?php if (!empty($commendation['grades'])): ?>
                                        <?php foreach ($commendation['grades'] as $gradeIndex => $grade): ?>
                                            <div class="section">
                                                <h4>Grade <?php echo $gradeIndex + 1; ?></h4>
                                                <div class="input-group">
                                                    <label for="commendations[<?php echo $index; ?>][grades][<?php echo $gradeIndex; ?>][name]">Name:</label>
                                                    <input type="text" id="commendations[<?php echo $index; ?>][grades][<?php echo $gradeIndex; ?>][name]" name="commendations[<?php echo $index; ?>][grades][<?php echo $gradeIndex; ?>][name]" value="<?php echo htmlspecialchars($grade['name']); ?>">
                                                </div>
                                                <div class="input-group">
                                                    <label for="commendations[<?php echo $index; ?>][grades][<?php echo $gradeIndex; ?>][description]">Description:</label>
                                                    <textarea id="commendations[<?php echo $index; ?>][grades][<?php echo $gradeIndex; ?>][description]" name="commendations[<?php echo $index; ?>][grades][<?php echo $gradeIndex; ?>][description]"><?php echo htmlspecialchars($grade['description']); ?></textarea>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <button type="button" onclick="addGrade(<?php echo $index; ?>)">Add Grade</button>
                            </div>
                            <div class="input-group">
                                <label>Rewards:</label>
                                <div class="repeater" id="rewardsRepeater<?php echo $index; ?>">
                                    <?php if (!empty($commendation['rewards'])): ?>
                                        <?php foreach ($commendation['rewards'] as $rewardIndex => $reward): ?>
                                            <div class="section">
                                                <h4>Reward <?php echo $rewardIndex + 1; ?></h4>
                                                <div class="input-group">
                                                    <label for="commendations[<?php echo $index; ?>][rewards][<?php echo $rewardIndex; ?>][type]">Type:</label>
                                                    <input type="text" id="commendations[<?php echo $index; ?>][rewards][<?php echo $rewardIndex; ?>][type]" name="commendations[<?php echo $index; ?>][rewards][<?php echo $rewardIndex; ?>][type]" value="<?php echo htmlspecialchars($reward['type']); ?>">
                                                </div>
                                                <div class="input-group">
                                                    <label for="commendations[<?php echo $index; ?>][rewards][<?php echo $rewardIndex; ?>][description]">Description:</label>
                                                    <input type="text" id="commendations[<?php echo $index; ?>][rewards][<?php echo $rewardIndex; ?>][description]" name="commendations[<?php echo $index; ?>][rewards][<?php echo $rewardIndex; ?>][description]" value="<?php echo htmlspecialchars($reward['description']); ?>">
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <button type="button" onclick="addReward(<?php echo $index; ?>)">Add Reward</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button type="button" onclick="addCommendation()">Add Commendation</button>
        <button type="submit">Save</button>
    </form>
</body>
</html>
