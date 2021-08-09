<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @page {
            margin: 20px;
        }

        p {
            margin: 5px
        }
    </style>
</head>

<body>
    <p style="text-align: center;font-weight: bold;font-size: 13pt;">ໃບນຳສົ່ງສິນຄ້າ</p>
<!-- 
    <p style="font-size: 12pt;font-weight: bold;">ເລກບິນ :
        {{ $id }}
    </p> -->

    <barcode code="{{ $id }}" type="C128B" height="2" />
    <p style="font-size: 12pt;text-align: center;">
        {{ $id }}
    </p>
    <hr>

    <p style="font-size: 11pt;">ວັນທີ :
        {{ $date }}
    </p>

    <p style="font-size: 11pt;">ເຖິງສາຂາ :
        {{ $to }}
    </p>

    <p style="font-size: 11pt;">ລາຍລະອຽດ :
        {{ $detail }}
    </p>

</body>

</html>