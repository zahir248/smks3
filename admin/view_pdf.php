<?php
$file = $_GET['file'] ?? '';

$file = basename($file); // security
$path = "uploads/pdf/" . $file;

if (!file_exists($path)) {
    die("File tak jumpa");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Preview PDF</title>

    <style>
        body {
            font-family: Arial;
            background: #f5f5f5;
            padding: 20px;
        }

        h2 {
            text-align: center;
        }

        .pdf-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .pdf-grid canvas {
            width: 100%;
            border-radius: 10px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            background: white;
        }

        @media (max-width: 768px) {
            .pdf-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

</head>
<body>

<h2>Preview PDF</h2>

<div id="pdf-container" class="pdf-grid"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>

<script>
const url = "<?php echo $path; ?>";

pdfjsLib.getDocument(url).promise.then(async function(pdf) {

    const container = document.getElementById('pdf-container');

    for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {

        const page = await pdf.getPage(pageNum);

        const scale = 1.2;
        const viewport = page.getViewport({ scale: scale });

        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');

        canvas.height = viewport.height;
        canvas.width = viewport.width;

        container.appendChild(canvas);

        await page.render({
            canvasContext: context,
            viewport: viewport
        }).promise;
    }
});
</script>

</body>
</html>