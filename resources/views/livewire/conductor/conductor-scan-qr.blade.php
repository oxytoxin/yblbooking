<div x-data="{scanner:null}" x-init="
        $nextTick(() => {
            console.log('Scanning...')
            scanner = new QrScanner(
                $refs.stream, 
                result => {
                    window.location.href = '/conductor/booking/'+result.data;
                },
                {
                    returnDetailedScanResult: true,
                    highlightScanRegion: true,
                }
                )
            scanner.start();
        })
        ">
    <video x-ref="stream"></video>
</div>