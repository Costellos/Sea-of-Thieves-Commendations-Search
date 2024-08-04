document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("searchInput");
    const commendationList = document.getElementById("commendationList");

    fetch("assets/json/commendations.json")
        .then(response => response.json())
        .then(data => {
            const rewardTypes = data.rewardTypes;
            const commendations = data.commendations;

            displayCommendations(commendations, rewardTypes);
            
            searchInput.addEventListener("input", () => {
                const searchTerm = searchInput.value.toLowerCase();
                const filteredCommendations = commendations.filter(commendation => 
                    commendation.name.toLowerCase().includes(searchTerm) || 
                    commendation.description.toLowerCase().includes(searchTerm) ||
                    (commendation.grades && commendation.grades.some(grade => 
                        grade.name.toLowerCase().includes(searchTerm) || 
                        grade.description.toLowerCase().includes(searchTerm))) ||
                    (commendation.rewards && commendation.rewards.some(reward => 
                        reward.type.toLowerCase().includes(searchTerm) || 
                        reward.description.toLowerCase().includes(searchTerm))) ||
                    (commendation.timeLimited && commendation.dates.toLowerCase().includes(searchTerm)) ||
                    (commendation.section && commendation.section.name.toLowerCase().includes(searchTerm)) ||
                    (commendation.section && commendation.section.subSection && commendation.section.subSection.toLowerCase().includes(searchTerm)) ||
                    (commendation.image && commendation.image.toLowerCase().includes(searchTerm))
                );
                displayCommendations(filteredCommendations, rewardTypes);
            });
        })
        .catch(error => console.error("Error loading commendations:", error));

    function displayCommendations(commendations, rewardTypes) {
        commendationList.innerHTML = "";
        commendations.forEach(commendation => {
            const item = document.createElement("div");
            item.className = "commendation-item";
            item.innerHTML = `
                <div class="commendation-image"><img src="${commendation.image}" alt="${commendation.name}"></div>
                <div class="commendation-name">${commendation.name}</div>
                <div class="commendation-description">${commendation.description}</div>
                ${commendation.grades ? `
                    <div class="commendation-grades">
                        ${commendation.grades.map(grade => `
                            <div class="commendation-grade">
                                <div class="grade-name">${grade.name}</div>
                                <div class="grade-description">${grade.description}</div>
                            </div>
                        `).join('')}
                    </div>
                ` : ''}
                ${commendation.rewards ? `
                    <div class="commendation-rewards">
                        <div class="reward-title">Rewards</div>
                        ${commendation.rewards.map(reward => `
                            <div class="reward">
                                <img class="reward-icon" src="${rewardTypes[reward.type]}" alt="${reward.type}">
                                <span class="reward-description">${reward.description}</span>
                            </div>
                        `).join('')}
                    </div>
                ` : ''}
                ${commendation.timeLimited ? `
                    <div class="commendation-timelimited">
                        Time Limited: ${commendation.dates}
                    </div>
                ` : ''}
                ${commendation.section ? `
                    <div class="commendation-section">
                        Section: ${commendation.section.name}
                        ${commendation.section.subSection ? ` - Sub Section: ${commendation.section.subSection}` : ''}
                    </div>
                ` : ''}
            `;
            commendationList.appendChild(item);
        });
    }
});
