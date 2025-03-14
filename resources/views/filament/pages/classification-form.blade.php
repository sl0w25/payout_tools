<x-filament-panels::page>
    @if($formVisible)
        <div class="mt-4 p-4 border rounded-lg" wire:key="encode-form">
           {{$this->form }}
        </div>

        <x-filament::section collapsible wire:key="privacy-section">
            <x-slot name="heading"> DATA PRIVACY DECLARATION </x-slot>
            All data and information indicated herein shall be used for identification purposes for the implementation of disaster risk reduction and management (DRRM) programs, projects and activities and its disclosure shall be in compliance to Republic Act 10173 (Data Privacy Act of 2012).
        </x-filament::section>

        <x-filament-panels::form.actions :actions="$this->getFormActions()" />
    @else
        <div class="space-y-6" id="qr-dv" wire:key="qr-scanner">
            <video id="interactive" class="w-100 rounded-md"></video>
         </div>
    @endif
</x-filament-panels::page>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script>
        let scanner;

        function startScanner() {
            const errorContainer = document.getElementById('error-container');
            const successContainer = document.getElementById('success-container');

            scanner = new Instascan.Scanner({ video: document.getElementById('interactive') });

            scanner.addListener('scan', function (content) {
            console.log('QR Code Scanned:', content);

            // Stop scanner immediately after scanning
            if (scanner) {
                scanner.stop();
            }

            // Hide scanner video
            document.getElementById('interactive').style.display = 'none';

            // Prepare FormData for submission
            const formData = new FormData();
            formData.append('qr_number', content);

            fetch("{{ route('hired-qr') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.text())
            .then(text => {
                console.log("Server response:", text);

                try{
                    let data = JSON.parse(text);
                    console.log("Parsed JSON response:", data);

                if (data.error) {
                    Swal.fire({
                        title: 'Error!',
                        text: data.error,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });

                    // Restart scanner if an error occurs
                    document.getElementById('interactive').style.display = 'block';

                    startScanner();
                } else if(data.success){
                    Swal.fire({
                        title: 'Record Found!',
                        text: `Beneficiary: ${data.data.name}\nProvince: ${data.data.province}`,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });

                    // Dispatch the Livewire event
                    Livewire.dispatch("setSearchQuery", { qr_number: content});

                    // Force Livewire to refresh UI
                    setTimeout(() => {
                        Livewire.dispatch("setSearchQuery");
                    }, 500);
                }
             } catch (error) {
            console.error("Error parsing JSON:", error);
            Swal.fire({
                title: 'Server Error!',
                html: `<pre>${text}</pre>`,
                icon: 'error',
                confirmButtonText: 'OK'
            });

            // Restart scanner in case of an error
            startScanner();
        }
            })
            .catch(error => {
                console.error("Fetch error:", error);
                Swal.fire({
                    title: 'Network Error!',
                    html: `<pre>${data}</pre>`,
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });

                // Restart scanner in case of network error
                document.getElementById('interactive').style.display = 'block';
                startScanner();
            });
        });



            Instascan.Camera.getCameras()
                .then(function (cameras) {
                    if (cameras.length > 0) {
                        scanner.start(cameras[0]);
                    } else {
                        console.error('No cameras found.');
                        Swal.fire({
                            title: 'No Camera Found!',
                            text: 'Please ensure your camera is connected.',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(function (err) {
                    console.error('Camera access error:', err);
                    Swal.fire({
                        title: 'Camera Error!',
                        text: 'Unable to access camera: ' + err,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
        }

        document.addEventListener('DOMContentLoaded', startScanner);
    </script>








