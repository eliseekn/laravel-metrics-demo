<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Laravel Metrics Demo | Dashboard</title>
</head>
<body>

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-md-center flex-md-row flex-column mb-4">
            <h1>Dashboard</h1>

            <div>
                <select id="users-trends" class="form-select">
                    <option value="day" {{ request()->get('period') === 'day' ? 'selected' : '' }}>This week</option>
                    <option value="last_week" {{ request()->get('period') === 'last_week' ? 'selected' : '' }}>Since last week</option>
                    <option value="week" {{ request()->get('period') === 'week' ? 'selected' : '' }}>This month</option>
                    <option value="last_month" {{ request()->get('period') === 'last_month' ? 'selected' : '' }}>Since last month</option>
                    <option value="quater_year" {{ request()->get('period') === 'quater_year' ? 'selected' : '' }}>This quater year</option>
                    <option value="half_year" {{ request()->get('period') === 'half_year' ? 'selected' : '' }}>This half year</option>
                    <option value="month" {{ request()->get('period') === 'month' ? 'selected' : '' }}>This year</option>
                    <option value="last_year" {{ request()->get('period') === 'last_year' ? 'selected' : '' }}>Since last year</option>
                    <option value="year" {{ request()->get('period') === 'year' ? 'selected' : '' }}>Last 5 years</option>
                    <option value="custom" {{ !in_array(request()->get('period'), ['last_week', 'day', 'week', 'quater_year', 'half_year', 'month', 'year', 'last_month', 'last_year']) && !is_null(request()->get('period')) ? 'selected' : '' }}>Custom</option>
                </select>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
            <div class="col">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title text-capitalize">Total users</h5>
                        <p class="card-text fs-2 fw-bold mb-1">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title text-capitalize">Total products</h5>
                        <p class="card-text fs-2 fw-bold mb-1">{{ $totalProducts }}</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title text-capitalize">Total orders</h5>
                        <p class="card-text fs-2 fw-bold mb-1">{{ $totalOrders }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 mt-4">
            <div class="col">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title text-capitalize">Total users (today)</h5>
                        <p class="card-text fs-2 fw-bold mb-1">{{ $totalUsersToday['count'] ?? $totalUsersToday }}</p>
                        @if (!empty($totalUsersToday['variation']))
                            <p class="card-text fs-6">{{ $totalUsersToday['variation']['type'] }} of {{ $totalUsersToday['variation']['value'] }} since yesterday</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title text-capitalize">Total products (today)</h5>
                        <p class="card-text fs-2 fw-bold mb-1">{{ $totalProductsToday['count'] ?? $totalProductsToday }}</p>
                        @if (!empty($totalProductsToday['variation']))
                            <p class="card-text fs-6">{{ $totalProductsToday['variation']['type'] }} of {{ $totalProductsToday['variation']['value'] }} since yesterday</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title text-capitalize">Total orders (today)</h5>
                        <p class="card-text fs-2 fw-bold mb-1">{{ $totalOrdersToday['count'] ?? $totalOrdersToday }}</p>
                        @if (!empty($totalOrdersToday['variation']))
                            <p class="card-text fs-6">{{ $totalOrdersToday['variation']['type'] }} of {{ $totalOrdersToday['variation']['value'] }} since yesterday</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title text-capitalize mb-3">Orders Status</h5>
                        <div style="height: 350px">
                            <canvas id="orders-status-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title text-capitalize mb-3">Products Status</h5>
                        <div style="height: 350px">
                            <canvas id="products-status-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title text-capitalize">Registered users</h5>
                        <canvas id="users-chart" height="250"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title text-capitalize mb-3">Orders</h5>
                        <canvas id="orders-chart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function drawUsersChart(chartData) {
            let ctx = document.querySelector('#users-chart')

            return new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Users',
                        fill: false,
                        data: chartData.data,
                        borderWidth: 3,
                    }]
                },
                options: {
                    responsive: true,
                    title: {
                        display: false,
                    }
                }
            })
        }

        function drawOrdersChart(chartData) {
            let ctx = document.querySelector('#orders-chart')

            return new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Orders',
                        fill: false,
                        data: chartData.data,
                        borderWidth: 3,
                    }]
                },
                options: {
                    responsive: true,
                    title: {
                        display: false,
                    }
                }
            })
        }

        function drawOrdersStatusChart(chartData) {
            let ctx = document.querySelector('#orders-status-chart')

            return new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Orders status',
                        fill: false,
                        data: chartData.data,
                        borderWidth: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    title: {
                        display: false,
                    }
                }
            })
        }

        function drawProductsStatusChart(chartData) {
            let ctx = document.querySelector('#products-status-chart')

            return new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Products in stock',
                        fill: false,
                        data: chartData.data,
                        borderWidth: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    title: {
                        display: false,
                    }
                }
            })
        }

        document.addEventListener('DOMContentLoaded', () => {
            drawUsersChart({!! $usersTrends !!})
            drawOrdersChart({!! $ordersTrends !!})
            drawOrdersStatusChart({!! $ordersStatusTrends !!})
            drawProductsStatusChart({!! $productsStatusTrends !!})

            document.querySelector('#users-trends').addEventListener('change', e => {
                if (e.target.value !== 'custom') {
                    window.location.href = "{{ config('app.url') }}?period=" + e.target.value
                } else {
                    Swal.fire({
                        html: `
                            <p class="mt-3">Define period :</p>

                            <div>
                                <label for="start_date" class="form-label text-start">Start</label>
                                <input type="date" id="start_date" name="start_date" class="swal2-input">
                            </div>

                            <label for="end_date" class="form-label text-start">End&nbsp;&nbsp;</label>
                            <input type="date" id="end_date" name="end_date" class="swal2-input">
                        `,
                        preConfirm: () => {
                            const startDate = Swal.getPopup().querySelector('#start_date').value
                            const endDate = Swal.getPopup().querySelector('#end_date').value

                            if (!startDate || !endDate) {
                                Swal.showValidationMessage('You must define start and end date')
                            }

                            return { startDate: startDate, endDate: endDate }
                        },
                        confirmButtonColor: '#0071bc',
                        showCancelButton: true,
                        cancelButtonText: 'Annuler',
                    }).then(result => {
                        if (!result.isConfirmed) {
                            return
                        }

                        window.location.href = "{{ config('app.url') }}?period=" + result.value.startDate + '~' + result.value.endDate
                    })
                }
            })
        })
    </script>

</body>
</html>
