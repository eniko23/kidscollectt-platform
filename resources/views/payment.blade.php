<x-layouts.storefront>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4 text-center">Ödeme Ekranı</h2>
                
                <div style="width: 100%; margin: 0 auto; display: table;">
                    <script src="https://www.paytr.com/js/iframeResizer.min.js"></script>
                    <iframe src="https://www.paytr.com/odeme/guvenli/{{ $token }}" id="paytriframe" frameborder="0" scrolling="no" style="width: 100%;"></iframe>
                    <script>iFrameResize({},'#paytriframe');</script>
                </div>

            </div>
        </div>
    </div>
</x-layouts.storefront>