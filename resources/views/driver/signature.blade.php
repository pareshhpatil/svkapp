@extends('layouts.app')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>


<div id="appCapsule" class="full-height">

<div class="signature-container">
    <canvas id="signature-pad" width="400" height="200" style="border:1px solid #ccc;"></canvas>
    <br>
    <button type="button" id="clear-btn">Clear</button>
    <button type="button" id="save-btn">Save</button>
</div>

<form id="signature-form" method="POST" action="">
    @csrf
    <input type="hidden" name="signature" id="signature-input">
</form>

</div>


@endsection



@section('footer')




<script>
    const canvas = document.getElementById('signature-pad');
    const signaturePad = new SignaturePad(canvas);

    function setCanvasWhiteBackground() {
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = "#ffffff"; // white
        ctx.fillRect(0, 0, canvas.width, canvas.height);
    }

    // Set white background when canvas is cleared or initialized
    setCanvasWhiteBackground();
    
    document.getElementById('clear-btn').addEventListener('click', function () {
        signaturePad.clear();
    });

    document.getElementById('save-btn').addEventListener('click', function () {
        if (!signaturePad.isEmpty()) {
            const dataURL = signaturePad.toDataURL();
            document.getElementById('signature-input').value = dataURL;
            document.getElementById('signature-form').submit();
        } else {
            alert("Please sign before saving.");
        }
    });
</script>






@endsection
