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
        .section {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
        }
        .section h2 {
            margin-top: 0;
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
            newCommendation.className = 'section';
            newCommendation.innerHTML = `
                <h3>Commendation ${index + 1}</h3>
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
                        <div class="section">
                            <h4>Grade ${gradeIndex + 1}</h4>
                            <div class="input-group">
                                <label for="commendations[${index}][grades][${gradeIndex}][name]">Name:</label>
                                <input type="text" id="commendations[${index}][grades][${gradeIndex}][name]" name="commendations[${index}][grades][${gradeIndex}][name]">
                            </div>
                            <div class="input-group">
                                <label for="commendations[${index}][grades][${gradeIndex}][description]">Description:</label>
                                <textarea id="commendations[${index}][grades][${gradeIndex}][description]" name="commendations[${index}][grades][${gradeIndex}][description]"></textarea>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="addGrade(${index})">Add Grade</button>
                </div>
                <div class="input-group">
                    <label>Rewards:</label>
                    <div class="repeater" id="rewardsRepeater${index}">
                        <div class="section">
                            <h4>Reward ${rewardIndex + 1}</h4>
                            <div class="input-group">
                                <label for="commendations[${index}][rewards][${rewardIndex}][type]">Type:</label>
                                <input type="text" id="commendations[${index}][rewards][${rewardIndex}][type]" name="commendations[${index}][rewards][${rewardIndex}][type]">
                            </div>
                            <div class="input-group">
                                <label for="commendations[${index}][rewards][${rewardIndex}][description]">Description:</label>
                                <input type="text" id="commendations[${index}][rewards][${rewardIndex}][description]" name="commendations[${index}][rewards][${rewardIndex}][description]">
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="addReward(${index})">Add Reward</button>
                </div>
            `;
            repeater.appendChild(newCommendation);
            gradeCount[index] = 1;
            rewardCount[index] = 1;
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
                    <input type="text" id="commendations[${commIndex}][grades][${index}][name]" name="commendations[${commIndex}][grades][${index}][name]">
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

        function addRewardType() {
            const repeater = document.getElementById('rewardTypesRepeater');
            const index = rewardTypeCount++;
            
            const newRewardType = document.createElement('div');
            newRewardType.className = 'section';
            newRewardType.innerHTML = `
                <h3>Reward Type ${index + 1}</h3>
                <div class="input-group">
                    <label for="rewardTypes[${index}][name]">Name:</label>
                    <input type="text" id="rewardTypes[${index}][name]" name="rewardTypes[${index}][name]" required>
                </div>
                <div class="input-group">
                    <label for="rewardTypes[${index}][icon]">Icon Path:</label>
                    <input type="text" id="rewardTypes[${index}][icon]" name="rewardTypes[${index}][icon]" required>
                </div>
            `;
            repeater.appendChild(newRewardType);
        }

        let commendationCount = 0;
        const gradeCount = [];
        const rewardCount = [];
        let rewardTypeCount = 0;

        document.addEventListener('DOMContentLoaded', () => {
            //Ger data
            const data = <?php echo json_encode($jsonData); ?>;

            //If commendations
            if (Array.isArray(data.commendations)) {
                data.commendations.forEach((commendation, index) => {
                    commendationCount = Math.max(commendationCount, index + 1);
                    gradeCount[index] = commendation.grades ? commendation.grades.length : 0;
                    rewardCount[index] = commendation.rewards ? commendation.rewards.length : 0;

                    console.log(commendation.section);
                    
                    const repeater = document.getElementById('commendationsRepeater');
                    const newCommendation = document.createElement('div');
                    newCommendation.className = 'section';
                    newCommendation.innerHTML = `
                        <h3>Commendation ${index + 1}</h3>
                        <div class="input-group">
                            <label for="commendations[${index}][name]">Name:</label>
                            <input type="text" id="commendations[${index}][name]" name="commendations[${index}][name]" value="${commendation.name}" required>
                        </div>
                        <div class="input-group">
                            <label for="commendations[${index}][description]">Description:</label>
                            <textarea id="commendations[${index}][description]" name="commendations[${index}][description]" required>${commendation.description}</textarea>
                        </div>
                        <div class="input-group">
                            <label for="commendations[${index}][section]">Section:</label>
                            <input type="text" id="commendations[${index}][section]" name="commendations[${index}][section]" value="${commendation.section ? commendation.section : ''}">
                        </div>
                        <div class="input-group">
                            <label for="commendations[${index}][subSection]">Sub Section:</label>
                            <input type="text" id="commendations[${index}][subSection]" name="commendations[${index}][subSection]" value="${commendation.subSection ? commendation.subSection : ''}">
                        </div>
                        <div class="input-group">
                            <label for="commendations[${index}][image]">Image Path:</label>
                            <input type="text" id="commendations[${index}][image]" name="commendations[${index}][image]" value="${commendation.image ? commendation.image : ''}">
                        </div>
                        <div class="input-group">
                            <label for="timeLimited${index}">Time Limited:</label>
                            <input type="checkbox" id="timeLimited${index}" name="commendations[${index}][timeLimited]" value="true" ${commendation.timeLimited ? 'checked' : ''} onclick="toggleTimeLimitedFields(${index})">
                        </div>
                        <div id="timeLimitedSection${index}" class="time-limited-section ${commendation.timeLimited ? 'active' : ''}">
                            <div class="input-group">
                                <label for="commendations[${index}][timeLimitedStart]">Start Date:</label>
                                <input type="date" id="commendations[${index}][timeLimitedStart]" name="commendations[${index}][timeLimitedStart]" value="${commendation.timeLimitedStart ? commendation.timeLimitedStart : ''}">
                            </div>
                            <div class="input-group">
                                <label for="commendations[${index}][timeLimitedEnd]">End Date:</label>
                                <input type="date" id="commendations[${index}][timeLimitedEnd]" name="commendations[${index}][timeLimitedEnd]" value="${commendation.timeLimitedEnd ? commendation.timeLimitedEnd : ''}">
                            </div>
                        </div>
                        <div class="input-group">
                            <label>Grades:</label>
                            <div class="repeater" id="gradesRepeater${index}">
                                ${commendation.grades ? commendation.grades.map((grade, gIndex) => `
                                    <div class="section">
                                        <h4>Grade ${gIndex + 1}</h4>
                                        <div class="input-group">
                                            <label for="commendations[${index}][grades][${gIndex}][name]">Name:</label>
                                            <input type="text" id="commendations[${index}][grades][${gIndex}][name]" name="commendations[${index}][grades][${gIndex}][name]" value="${grade.name}">
                                        </div>
                                        <div class="input-group">
                                            <label for="commendations[${index}][grades][${gIndex}][description]">Description:</label>
                                            <textarea id="commendations[${index}][grades][${gIndex}][description]" name="commendations[${index}][grades][${gIndex}][description]">${grade.description}</textarea>
                                        </div>
                                    </div>
                                `).join('') : ''}
                            </div>
                            <button type="button" onclick="addGrade(${index})">Add Grade</button>
                        </div>
                        <div class="input-group">
                            <label>Rewards:</label>
                            <div class="repeater" id="rewardsRepeater${index}">
                                ${commendation.rewards ? commendation.rewards.map((reward, rIndex) => `
                                    <div class="section">
                                        <h4>Reward ${rIndex + 1}</h4>
                                        <div class="input-group">
                                            <label for="commendations[${index}][rewards][${rIndex}][type]">Type:</label>
                                            <input type="text" id="commendations[${index}][rewards][${rIndex}][type]" name="commendations[${index}][rewards][${rIndex}][type]" value="${reward.type}">
                                        </div>
                                        <div class="input-group">
                                            <label for="commendations[${index}][rewards][${rIndex}][description]">Description:</label>
                                            <input type="text" id="commendations[${index}][rewards][${rIndex}][description]" name="commendations[${index}][rewards][${rIndex}][description]" value="${reward.description}">
                                        </div>
                                    </div>
                                `).join('') : ''}
                            </div>
                            <button type="button" onclick="addReward(${index})">Add Reward</button>
                        </div>
                    `;
                    repeater.appendChild(newCommendation);
                });
            }

            // Initialize reward types repeater
            const rewardTypesRepeater = document.getElementById('rewardTypesRepeater');
            Object.keys(data.rewardTypes).forEach((key, index) => {
                const rewardTypes = data.rewardTypes;
                const repeater = document.getElementById('rewardTypesRepeater');
                const newRewardType = document.createElement('div');
                newRewardType.className = 'section';
                newRewardType.innerHTML = `
                    <h3>Reward Type ${index + 1}</h3>
                    <div class="input-group">
                        <label for="rewardTypes[${index}][name]">Name:</label>
                        <input type="text" id="rewardTypes[${index}][name]" name="rewardTypes[${index}][name]" value="${rewardTypes[key].name}" required>
                    </div>
                    <div class="input-group">
                        <label for="rewardTypes[${index}][icon]">Icon Path:</label>
                        <input type="text" id="rewardTypes[${index}][icon]" name="rewardTypes[${index}][icon]" value="${rewardTypes[key].icon}" required>
                    </div>
                `;
                repeater.appendChild(newRewardType);
                rewardTypeCount++;
            });
        });
    </script>
</head>
<body>
    <h1>Edit Commendations</h1>
    <form action="save_json.php" method="post">
        <div id="commendationsRepeater"></div>
        <button type="button" onclick="addCommendation()">Add Commendation</button>
        
        <h2>Reward Types</h2>
        <div id="rewardTypesRepeater"></div>
        <button type="button" onclick="addRewardType()">Add Reward Type</button>

        <button type="submit">Save Changes</button>
    </form>
</body>
</html>
