<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
    <p style="text-align: center;font-weight: bold;">ໃບນຳສົ່ງສິນຄ້າ</p>

    <p style="font-size: 12pt;">ເລກບິນ :
        <?php echo $id; ?>
    </p>

    <p style="font-size: 12pt;">ເຖິງສາຂາ :
        <?php echo $to; ?>
    </p>

    <p style="font-size: 12pt;">ລວມເປັນເງິນ :
        <?php echo $price; ?> ກີບ
    </p>

    <barcode code="{{ $id }}" type="C128B" height="1" text="2" />

</body>

</html>
