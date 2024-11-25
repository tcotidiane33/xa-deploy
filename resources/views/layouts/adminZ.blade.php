<!DOCTYPE HTML>
<html>

<head>
    <title>@yield('title', 'Admin Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('web/css/bootstrap.css') }}" rel='stylesheet' type='text/css' />

    <!-- Custom CSS -->
    <link href="{{ asset('web/css/style.css') }}" rel='stylesheet' type='text/css' />

    <!-- font-awesome icons CSS -->
    <link href="{{ asset('web/css/font-awesome.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flowbite@1.4.7/dist/flowbite.min.css">
    <!-- side nav css file -->
    <link href="{{ asset('web/css/SidebarNav.min.css') }}" media='all' rel='stylesheet' type='text/css' />
    <!-- Dans la section head -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .main-content {
            padding: 1rem;
            /* margin-left: 1rem; */
        }

        body {
            background: url('assets/mp2.svg');
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @stack('styles')
</head>

<body class="cbp-spmenu-push">
    <div class="main">
        @include('components.partials.admin.sidebar')

        <!-- header-starts -->
        @include('components.partials.admin.header')
        <!-- //header-ends -->

        <!-- main content start-->
        <div id="page-wrapper">
            @yield('content')
        </div>
        <div id="alert-box" class="alert alert-success position-fixed top-0 end-0 m-4" role="alert"
            style="display: none;">
            <h4 class="alert-heading">Alerts</h4>
            <p id="alert-message"></p>
        </div>

        {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}
        <!--footer-->
        <div class="mt-4 pb-0">
            @include('components.partials.admin.footer')
        </div>
        <!--//footer-->
    </div>
    <script>
        function showAlert(message, type) {
            var alertBox = document.getElementById('alert-box');
            var alertMessage = document.getElementById('alert-message');

            alertMessage.textContent = message;
            alertBox.classList.remove('alert-success', 'alert-danger');
            alertBox.classList.add('alert-' + type);
            alertBox.style.display = 'block';

            setTimeout(function() {
                alertBox.style.display = 'none';
            }, 3000); // masquer après 3 secondes
        }

        // exemple d'utilisation
        @if (session('success'))
            <
            script >
                showAlert('{{ session('success') }}', 'success');
    </script>
    @endif

    @if (session('error'))
        <script>
            showAlert('{{ session('error') }}', 'danger');
        </script>
    @endif
    </script>
    <!-- js-->
    <script src="{{ asset('web/js/jquery-1.11.1.min.js') }}"></script>
    <script src="{{ asset('web/js/modernizr.custom.js') }}"></script>

    <!-- Metis Menu -->
    <script src="{{ asset('web/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('web/js/custom.js') }}"></script>

    <!-- side nav js -->
    <script src="{{ asset('web/js/SidebarNav.min.js') }}" type='text/javascript'></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset('web/js/bootstrap.js') }}"></script>

    <script src="{{ asset('web/js/Chart.bundle.js') }}"></script>
    <script src="{{ asset('web/js/utils.js') }}"></script>

    <script>
        var MONTHS = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October",
            "November", "December"
        ];
        var color = Chart.helpers.color;
        var barChartData = {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [{
                label: 'Dataset 1',
                backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
                borderColor: window.chartColors.red,
                borderWidth: 1,
                data: [
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor()
                ]
            }, {
                label: 'Dataset 2',
                backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
                borderColor: window.chartColors.blue,
                borderWidth: 1,
                data: [
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor()
                ]
            }]

        };

        window.onload = function() {
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData,
                options: {
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Chart.js Bar Chart'
                    }
                }
            });

        };

        document.getElementById('randomizeData').addEventListener('click', function() {
            var zero = Math.random() < 0.2 ? true : false;
            barChartData.datasets.forEach(function(dataset) {
                dataset.data = dataset.data.map(function() {
                    return zero ? 0.0 : randomScalingFactor();
                });

            });
            window.myBar.update();
        });

        var colorNames = Object.keys(window.chartColors);
        document.getElementById('addDataset').addEventListener('click', function() {
            var colorName = colorNames[barChartData.datasets.length % colorNames.length];;
            var dsColor = window.chartColors[colorName];
            var newDataset = {
                label: 'Dataset ' + barChartData.datasets.length,
                backgroundColor: color(dsColor).alpha(0.5).rgbString(),
                borderColor: dsColor,
                borderWidth: 1,
                data: []
            };

            for (var index = 0; index < barChartData.labels.length; ++index) {
                newDataset.data.push(randomScalingFactor());
            }

            barChartData.datasets.push(newDataset);
            window.myBar.update();
        });

        document.getElementById('addData').addEventListener('click', function() {
            if (barChartData.datasets.length > 0) {
                var month = MONTHS[barChartData.labels.length % MONTHS.length];
                barChartData.labels.push(month);

                for (var index = 0; index < barChartData.datasets.length; ++index) {
                    //window.myBar.addData(randomScalingFactor(), index);
                    barChartData.datasets[index].data.push(randomScalingFactor());
                }

                window.myBar.update();
            }
        });

        document.getElementById('removeDataset').addEventListener('click', function() {
            barChartData.datasets.splice(0, 1);
            window.myBar.update();
        });

        document.getElementById('removeData').addEventListener('click', function() {
            barChartData.labels.splice(-1, 1); // remove the label first

            barChartData.datasets.forEach(function(dataset, datasetIndex) {
                dataset.data.pop();
            });

            window.myBar.update();
        });
    </script>
    <!-- new added graphs chart js-->

    <!-- Classie --><!-- for toggle left push menu script -->
    <script src="{{ asset('web/js/classie.js') }}"></script>
    <script>
        var menuLeft = document.getElementById('cbp-spmenu-s1'),
            showLeftPush = document.getElementById('showLeftPush'),
            body = document.body;

        showLeftPush.onclick = function() {
            classie.toggle(this, 'active');
            classie.toggle(body, 'cbp-spmenu-push-toright');
            classie.toggle(menuLeft, 'cbp-spmenu-open');
            disableOther('showLeftPush');
        };


        function disableOther(button) {
            if (button !== 'showLeftPush') {
                classie.toggle(showLeftPush, 'disabled');
            }
        }
    </script>
    <!-- //Classie --><!-- //for toggle left push menu script -->

    <!--scrolling js-->
    <script src="{{ asset('web/js/jquery.nicescroll.js') }}"></script>
    <script src="{{ asset('web/js/scripts.js') }}"></script>
    <!--//scrolling js-->

    <!-- side nav js -->
    <script src="{{ asset('web/js/SidebarNav.min.js') }}" type='text/javascript'></script>
    <script>
        $('.sidebar-menu').SidebarNav()
    </script>
    <!-- //side nav js -->

    <!-- for index page weekly sales java script -->
    <script src="{{ asset('web/js/SimpleChart.js') }}"></script>
    <script>
        var graphdata1 = {
            linecolor: "#CCA300",
            title: "Monday",
            values: [{
                    X: "6:00",
                    Y: 10.00
                },
                {
                    X: "7:00",
                    Y: 20.00
                },
                {
                    X: "8:00",
                    Y: 40.00
                },
                {
                    X: "9:00",
                    Y: 34.00
                },
                {
                    X: "10:00",
                    Y: 40.25
                },
                {
                    X: "11:00",
                    Y: 28.56
                },
                {
                    X: "12:00",
                    Y: 18.57
                },
                {
                    X: "13:00",
                    Y: 34.00
                },
                {
                    X: "14:00",
                    Y: 40.89
                },
                {
                    X: "15:00",
                    Y: 12.57
                },
                {
                    X: "16:00",
                    Y: 28.24
                },
                {
                    X: "17:00",
                    Y: 18.00
                },
                {
                    X: "18:00",
                    Y: 34.24
                },
                {
                    X: "19:00",
                    Y: 40.58
                },
                {
                    X: "20:00",
                    Y: 12.54
                },
                {
                    X: "21:00",
                    Y: 28.00
                },
                {
                    X: "22:00",
                    Y: 18.00
                },
                {
                    X: "23:00",
                    Y: 34.89
                },
                {
                    X: "0:00",
                    Y: 40.26
                },
                {
                    X: "1:00",
                    Y: 28.89
                },
                {
                    X: "2:00",
                    Y: 18.87
                },
                {
                    X: "3:00",
                    Y: 34.00
                },
                {
                    X: "4:00",
                    Y: 40.00
                }
            ]
        };
        var graphdata2 = {
            linecolor: "#00CC66",
            title: "Tuesday",
            values: [{
                    X: "6:00",
                    Y: 100.00
                },
                {
                    X: "7:00",
                    Y: 120.00
                },
                {
                    X: "8:00",
                    Y: 140.00
                },
                {
                    X: "9:00",
                    Y: 134.00
                },
                {
                    X: "10:00",
                    Y: 140.25
                },
                {
                    X: "11:00",
                    Y: 128.56
                },
                {
                    X: "12:00",
                    Y: 118.57
                },
                {
                    X: "13:00",
                    Y: 134.00
                },
                {
                    X: "14:00",
                    Y: 140.89
                },
                {
                    X: "15:00",
                    Y: 112.57
                },
                {
                    X: "16:00",
                    Y: 128.24
                },
                {
                    X: "17:00",
                    Y: 118.00
                },
                {
                    X: "18:00",
                    Y: 134.24
                },
                {
                    X: "19:00",
                    Y: 140.58
                },
                {
                    X: "20:00",
                    Y: 112.54
                },
                {
                    X: "21:00",
                    Y: 128.00
                },
                {
                    X: "22:00",
                    Y: 118.00
                },
                {
                    X: "23:00",
                    Y: 134.89
                },
                {
                    X: "0:00",
                    Y: 140.26
                },
                {
                    X: "1:00",
                    Y: 128.89
                },
                {
                    X: "2:00",
                    Y: 118.87
                },
                {
                    X: "3:00",
                    Y: 134.00
                },
                {
                    X: "4:00",
                    Y: 180.00
                }
            ]
        };
        var graphdata3 = {
            linecolor: "#FF99CC",
            title: "Wednesday",
            values: [{
                    X: "6:00",
                    Y: 230.00
                },
                {
                    X: "7:00",
                    Y: 210.00
                },
                {
                    X: "8:00",
                    Y: 214.00
                },
                {
                    X: "9:00",
                    Y: 234.00
                },
                {
                    X: "10:00",
                    Y: 247.25
                },
                {
                    X: "11:00",
                    Y: 218.56
                },
                {
                    X: "12:00",
                    Y: 268.57
                },
                {
                    X: "13:00",
                    Y: 274.00
                },
                {
                    X: "14:00",
                    Y: 280.89
                },
                {
                    X: "15:00",
                    Y: 242.57
                },
                {
                    X: "16:00",
                    Y: 298.24
                },
                {
                    X: "17:00",
                    Y: 208.00
                },
                {
                    X: "18:00",
                    Y: 214.24
                },
                {
                    X: "19:00",
                    Y: 214.58
                },
                {
                    X: "20:00",
                    Y: 211.54
                },
                {
                    X: "21:00",
                    Y: 248.00
                },
                {
                    X: "22:00",
                    Y: 258.00
                },
                {
                    X: "23:00",
                    Y: 234.89
                },
                {
                    X: "0:00",
                    Y: 210.26
                },
                {
                    X: "1:00",
                    Y: 248.89
                },
                {
                    X: "2:00",
                    Y: 238.87
                },
                {
                    X: "3:00",
                    Y: 264.00
                },
                {
                    X: "4:00",
                    Y: 270.00
                }
            ]
        };
        var graphdata4 = {
            linecolor: "Random",
            title: "Thursday",
            values: [{
                    X: "6:00",
                    Y: 300.00
                },
                {
                    X: "7:00",
                    Y: 410.98
                },
                {
                    X: "8:00",
                    Y: 310.00
                },
                {
                    X: "9:00",
                    Y: 314.00
                },
                {
                    X: "10:00",
                    Y: 310.25
                },
                {
                    X: "11:00",
                    Y: 318.56
                },
                {
                    X: "12:00",
                    Y: 318.57
                },
                {
                    X: "13:00",
                    Y: 314.00
                },
                {
                    X: "14:00",
                    Y: 310.89
                },
                {
                    X: "15:00",
                    Y: 512.57
                },
                {
                    X: "16:00",
                    Y: 318.24
                },
                {
                    X: "17:00",
                    Y: 318.00
                },
                {
                    X: "18:00",
                    Y: 314.24
                },
                {
                    X: "19:00",
                    Y: 310.58
                },
                {
                    X: "20:00",
                    Y: 312.54
                },
                {
                    X: "21:00",
                    Y: 318.00
                },
                {
                    X: "22:00",
                    Y: 318.00
                },
                {
                    X: "23:00",
                    Y: 314.89
                },
                {
                    X: "0:00",
                    Y: 310.26
                },
                {
                    X: "1:00",
                    Y: 318.89
                },
                {
                    X: "2:00",
                    Y: 518.87
                },
                {
                    X: "3:00",
                    Y: 314.00
                },
                {
                    X: "4:00",
                    Y: 310.00
                }
            ]
        };
        var Piedata = {
            linecolor: "Random",
            title: "Profit",
            values: [{
                    X: "Monday",
                    Y: 50.00
                },
                {
                    X: "Tuesday",
                    Y: 110.98
                },
                {
                    X: "Wednesday",
                    Y: 70.00
                },
                {
                    X: "Thursday",
                    Y: 204.00
                },
                {
                    X: "Friday",
                    Y: 80.25
                },
                {
                    X: "Saturday",
                    Y: 38.56
                },
                {
                    X: "Sunday",
                    Y: 98.57
                }
            ]
        };
        $(function() {
            $("#Bargraph").SimpleChart({
                ChartType: "Bar",
                toolwidth: "50",
                toolheight: "25",
                axiscolor: "#E6E6E6",
                textcolor: "#6E6E6E",
                showlegends: true,
                data: [graphdata4, graphdata3, graphdata2, graphdata1],
                legendsize: "140",
                legendposition: 'bottom',
                xaxislabel: 'Hours',
                title: 'Weekly Profit',
                yaxislabel: 'Profit in $'
            });
            $("#sltchartype").on('change', function() {
                $("#Bargraph").SimpleChart('ChartType', $(this).val());
                $("#Bargraph").SimpleChart('reload', 'true');
            });
            $("#Hybridgraph").SimpleChart({
                ChartType: "Hybrid",
                toolwidth: "50",
                toolheight: "25",
                axiscolor: "#E6E6E6",
                textcolor: "#6E6E6E",
                showlegends: true,
                data: [graphdata4],
                legendsize: "140",
                legendposition: 'bottom',
                xaxislabel: 'Hours',
                title: 'Weekly Profit',
                yaxislabel: 'Profit in $'
            });
            $("#Linegraph").SimpleChart({
                ChartType: "Line",
                toolwidth: "50",
                toolheight: "25",
                axiscolor: "#E6E6E6",
                textcolor: "#6E6E6E",
                showlegends: false,
                data: [graphdata4, graphdata3, graphdata2, graphdata1],
                legendsize: "140",
                legendposition: 'bottom',
                xaxislabel: 'Hours',
                title: 'Weekly Profit',
                yaxislabel: 'Profit in $'
            });
            $("#Areagraph").SimpleChart({
                ChartType: "Area",
                toolwidth: "50",
                toolheight: "25",
                axiscolor: "#E6E6E6",
                textcolor: "#6E6E6E",
                showlegends: true,
                data: [graphdata4, graphdata3, graphdata2, graphdata1],
                legendsize: "140",
                legendposition: 'bottom',
                xaxislabel: 'Hours',
                title: 'Weekly Profit',
                yaxislabel: 'Profit in $'
            });
            $("#Scatterredgraph").SimpleChart({
                ChartType: "Scattered",
                toolwidth: "50",
                toolheight: "25",
                axiscolor: "#E6E6E6",
                textcolor: "#6E6E6E",
                showlegends: true,
                data: [graphdata4, graphdata3, graphdata2, graphdata1],
                legendsize: "140",
                legendposition: 'bottom',
                xaxislabel: 'Hours',
                title: 'Weekly Profit',
                yaxislabel: 'Profit in $'
            });
            $("#Piegraph").SimpleChart({
                ChartType: "Pie",
                toolwidth: "50",
                toolheight: "25",
                axiscolor: "#E6E6E6",
                textcolor: "#6E6E6E",
                showlegends: true,
                showpielables: true,
                data: [Piedata],
                legendsize: "250",
                legendposition: 'right',
                xaxislabel: 'Hours',
                title: 'Weekly Profit',
                yaxislabel: 'Profit in $'
            });

            $("#Stackedbargraph").SimpleChart({
                ChartType: "Stacked",
                toolwidth: "50",
                toolheight: "25",
                axiscolor: "#E6E6E6",
                textcolor: "#6E6E6E",
                showlegends: true,
                data: [graphdata3, graphdata2, graphdata1],
                legendsize: "140",
                legendposition: 'bottom',
                xaxislabel: 'Hours',
                title: 'Weekly Profit',
                yaxislabel: 'Profit in $'
            });

            $("#StackedHybridbargraph").SimpleChart({
                ChartType: "StackedHybrid",
                toolwidth: "50",
                toolheight: "25",
                axiscolor: "#E6E6E6",
                textcolor: "#6E6E6E",
                showlegends: true,
                data: [graphdata3, graphdata2, graphdata1],
                legendsize: "140",
                legendposition: 'bottom',
                xaxislabel: 'Hours',
                title: 'Weekly Profit',
                yaxislabel: 'Profit in $'
            });
        });
    </script>
    <!-- //for index page weekly sales java script -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.getElementById('search-form');
            const searchInput = document.getElementById('input-31');

            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                performSearch();
            });

            searchInput.addEventListener('input', debounce(performSearch, 300));

            function performSearch() {
                const query = searchInput.value;
                if (query.length < 3) return; // Don't search for very short queries

                fetch(`/search?query=${encodeURIComponent(query)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Handle the search results here
                        console.log(data);
                        // You might want to update a part of your page with the results
                    })
                    .catch(error => console.error('Error:', error));
            }

            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }
        });
    </script>

    @stack('scripts')
    <!-- Juste avant la fermeture de la balise body -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>

</html>
