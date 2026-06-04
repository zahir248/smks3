<?php
if(isset($_FILES['pdf_file'])) {

    $file = $_FILES['pdf_file'];
    $name = time() . '_' . basename($file['name']);
    $target = "uploads/pdf/" . $name;

    // check file type
    if($file['type'] == "application/pdf") {

        if(move_uploaded_file($file['tmp_name'], $target)) {
            echo "Upload berjaya!";
            echo "<br><a href='view_pdf.php?file=$name'>Lihat PDF</a>";
        } else {
            echo "Upload gagal.";
        }

    } else {
        echo "Hanya PDF dibenarkan.";
    }
}