document.addEventListener('DOMContentLoaded', function () {

    // Highlight current day with enhanced styling
    const today = new Date();
    const dayNames = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
    const currentDay = dayNames[today.getDay()];

    const headers = document.querySelectorAll('.schedule-table th');
    headers.forEach((header, index) => {
        if (header.textContent.toLowerCase().includes(currentDay)) {
            header.classList.add('current-day');

            const rows = document.querySelectorAll('.schedule-table tbody tr');
            rows.forEach(row => {
                const cell = row.children[index];
                if (cell) {
                    cell.classList.add('current-day-cell');
                }
            });
        }
    });

    // Enhanced hover effects
    const tableRows = document.querySelectorAll('.schedule-table tbody tr');
    tableRows.forEach((row, index) => {
        row.addEventListener('mouseenter', function () {
            this.style.zIndex = '10';
        });

        row.addEventListener('mouseleave', function () {
            this.style.zIndex = '1';
        });
    });


    // Add CSS animations dynamically
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    `;
    document.head.appendChild(style);
});