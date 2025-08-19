jQuery(document).ready(function($) {
    // Condominium selector change handler
    $('#condominium-select').on('change', function() {
        var condominiumId = $(this).val();
        if (condominiumId) {
            window.location.href = 'admin.php?page=water-meter-readings&condominium_id=' + condominiumId;
        }
    });

    // Address filter change handler
    $('#address-filter').on('change', function() {
        var addressId = $(this).val();
        var params = new URLSearchParams(window.location.search);
        if (addressId && addressId !== '0') {
            params.set('address_id', addressId);
        } else {
            params.delete('address_id');
        }
        window.location.search = params.toString();
    });
    
    // Initialize charts if data is available
    if (typeof chartData !== 'undefined' && chartData.length > 0) {
        initializeCharts();
    }
    
    function initializeCharts() {
        // Prepare data for charts
        var labels = chartData.map(function(item) { return item.date; });
        var hotWaterData = chartData.map(function(item) { return item.hot_water; });
        var coldWaterData = chartData.map(function(item) { return item.cold_water; });
        
        // Calculate consumption differences
        var hotWaterConsumption = [];
        var coldWaterConsumption = [];
        
        for (var i = 1; i < hotWaterData.length; i++) {
            hotWaterConsumption.push(hotWaterData[i] - hotWaterData[i-1]);
            coldWaterConsumption.push(coldWaterData[i] - coldWaterData[i-1]);
        }
        
        var consumptionLabels = labels.slice(1); // Remove first date as we don't have consumption for it
        
        // Water Levels Chart
        var waterCtx = document.getElementById('waterChart').getContext('2d');
        var waterChart = new Chart(waterCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Kuuma vesi',
                    data: hotWaterData,
                    borderColor: '#ff6384',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    borderWidth: 2,
                    fill: false
                }, {
                    label: 'Kylmä vesi',
                    data: coldWaterData,
                    borderColor: '#36a2eb',
                    backgroundColor: 'rgba(54, 162, 235, 0.1)',
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Vesimittarin lukemat ajan kuluessa'
                    },
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        title: {
                            display: true,
                            text: 'Lukema (m³)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Päivämäärä'
                        }
                    }
                }
            }
        });
        
        // Consumption Chart (if we have enough data)
        if (consumptionLabels.length > 0) {
            var consumptionCtx = document.getElementById('consumptionChart').getContext('2d');
            var consumptionChart = new Chart(consumptionCtx, {
                type: 'bar',
                data: {
                    labels: consumptionLabels,
                    datasets: [{
                        label: 'Kuuman veden kulutus',
                        data: hotWaterConsumption,
                        backgroundColor: 'rgba(255, 99, 132, 0.8)',
                        borderColor: '#ff6384',
                        borderWidth: 1
                    }, {
                        label: 'Kylmän veden kulutus',
                        data: coldWaterConsumption,
                        backgroundColor: 'rgba(54, 162, 235, 0.8)',
                        borderColor: '#36a2eb',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Vedenkulutus ajan kuluessa'
                        },
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Kulutus (m³)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Päivämäärä'
                            }
                        }
                    }
                }
            });
        }
    }
    
    // Add some interactivity to the readings table
    $('.wp-list-table tbody tr').hover(
        function() {
            $(this).addClass('highlight');
        },
        function() {
            $(this).removeClass('highlight');
        }
    );
    
    // Add CSS for table row highlighting
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            .wp-list-table tbody tr.highlight {
                background-color: #f0f6fc !important;
            }
            .wp-list-table tbody tr:hover {
                background-color: #f9f9f9 !important;
            }
        `)
        .appendTo('head');
});
