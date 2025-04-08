document.addEventListener('DOMContentLoaded', function() {
    // Handle radio button selections for category and priority
    const radioGroups = document.querySelectorAll('.grid');

    radioGroups.forEach(group => {
        const radioInputs = group.querySelectorAll('input[type="radio"]');
        const labels = group.querySelectorAll('label');

        radioInputs.forEach((radio, index) => {
            radio.addEventListener('change', function() {
                // Remove active states from all labels in this group
                labels.forEach(label => {
                    label.classList.remove('ring-2');
                    if (label.classList.contains('hover:border-violet-500/20')) {
                        label.classList.remove('border-violet-500/20');
                    } else if (label.classList.contains('hover:border-green-500/20')) {
                        label.classList.remove('border-green-500/20');
                    } else if (label.classList.contains('hover:border-yellow-500/20')) {
                        label.classList.remove('border-yellow-500/20');
                    } else if (label.classList.contains('hover:border-red-500/20')) {
                        label.classList.remove('border-red-500/20');
                    }
                });

                // Add active state to selected label
                const selectedLabel = labels[index];
                selectedLabel.classList.add('ring-2');
                
                // Add appropriate border color based on the type
                if (selectedLabel.classList.contains('hover:border-violet-500/20')) {
                    selectedLabel.classList.add('border-violet-500/20');
                    selectedLabel.classList.add('ring-violet-500/50');
                } else if (selectedLabel.classList.contains('hover:border-green-500/20')) {
                    selectedLabel.classList.add('border-green-500/20');
                    selectedLabel.classList.add('ring-green-500/50');
                } else if (selectedLabel.classList.contains('hover:border-yellow-500/20')) {
                    selectedLabel.classList.add('border-yellow-500/20');
                    selectedLabel.classList.add('ring-yellow-500/50');
                } else if (selectedLabel.classList.contains('hover:border-red-500/20')) {
                    selectedLabel.classList.add('border-red-500/20');
                    selectedLabel.classList.add('ring-red-500/50');
                }
            });
        });
    });
});