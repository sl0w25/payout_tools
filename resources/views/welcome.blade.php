<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QR Code Attendance System</title>

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(to bottom, rgba(255,255,255,0.15) 0%, rgba(0,0,0,0.15) 100%), radial-gradient(at top center, rgba(255,255,255,0.40) 0%, rgba(0,0,0,0.40) 120%) #989898;
            background-blend-mode: multiply, multiply;
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 91.5vh;
        }

        .attendance-container {
            height: 90%;
            width: 90%;
            border-radius: 20px;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .attendance-container > div {
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            border-radius: 10px;
            padding: 30px;
        }

        .attendance-container > div:last-child {
            width: 64%;
            margin-left: auto;
        }
    </style>
</head>
<body>
    <div class="main">
        <div class="attendance-container row g-4">
            <div class="qr-container col-lg-4">
                <div class="scanner-con">
                    <h5 class="text-center">Scan your QR Code here</h5>
                    <video id="interactive" class="viewport w-100"></video>
                </div>

                <div id="error-container" class="text-danger fw-bold d-none"></div>
                <div id="success-container" class="text-success fw-bold d-none"></div>
                <div class="qr-detected-container d-none">
                    <form method="POST" action="{{ route('scan.qr') }}">
                        @csrf
                        <h4 class="text-center">Student QR Detected!</h4>
                        <input type="hidden" id="detected-qr-code" name="qr_number">
                    </form>
                </div>
            </div>

            <div class="attendance-list col-lg-8">
                <h4>List of Paid Beneficiaries</h4>
                <div class="table-container table-responsive">
                    <table class="table table-bordered text-center table-sm" id="attendanceTable">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Barangay</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Middle Name</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">Ext</th>
                                <th scope="col">Time of Payout</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceTableBody">
                            @forelse ($attendances as $attendance)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $attendance->barangay }}</td>
                                    <td>{{ $attendance->first_name }}</td>
                                    <td>{{ $attendance->middle_name }}</td>
                                    <td>{{ $attendance->last_name }}</td>
                                    <td>{{ $attendance->ext_name }}</td>
                                    <td>{{ $attendance->time_in }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No Records</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="pagination-container">
                        {{ $attendances->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Instascan JS -->
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

    <script>
        let scanner;

        function addAttendanceRow(attendance) {
            const tbody = document.querySelector("#attendanceTableBody");
            const row = document.createElement("tr");

            row.innerHTML = `
                <td>${attendance.id}</td>
                <td>${attendance.barangay}</td>
                <td>${attendance.first_name}</td>
                <td>${attendance.middle_name || ""}</td>
                <td>${attendance.last_name}</td>
                <td>${attendance.ext_name || ""}</td>
                <td>${attendance.time_in}</td>
            `;

            tbody.prepend(row);
        }

        function refreshTable(attendances) {
            const tbody = document.querySelector("#attendanceTableBody");
            tbody.innerHTML = ""; 

            attendances.forEach(attendance => {
                addAttendanceRow(attendance);
            });
        }

        function startScanner() {
            const errorContainer = document.getElementById('error-container');
            const successContainer = document.getElementById('success-container');

            scanner = new Instascan.Scanner({ video: document.getElementById('interactive') });

            scanner.addListener('scan', function (content) {
                console.log('QR Code Scanned:', content);

                
                errorContainer.classList.add('d-none');
                successContainer.classList.add('d-none');

                const formData = new FormData();
                formData.append('qr_number', content);

                fetch("{{ route('scan.qr') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: formData,
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.error || 'Unexpected error occurred');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        errorContainer.classList.remove('d-none');
                        errorContainer.innerHTML = data.error;
                    } else {
                        successContainer.classList.remove('d-none');
                        successContainer.innerHTML = data.message;
                        refreshTable(data.attendances);
                    }
                })
                .catch(error => {
                    console.error('Error:', error.message);
                    errorContainer.classList.remove('d-none');
                    errorContainer.textContent = error.message;
                });
            });

            Instascan.Camera.getCameras()
                .then(function (cameras) {
                    if (cameras.length > 0) {
                        scanner.start(cameras[0]);
                    } else {
                        console.error('No cameras found.');
                        alert('No cameras found.');
                    }
                })
                .catch(function (err) {
                    console.error('Camera access error:', err);
                    alert('Camera access error: ' + err);
                });
        }

        document.addEventListener('DOMContentLoaded', startScanner);
    </script>
</body>
</html>
