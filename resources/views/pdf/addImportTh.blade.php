<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @page {
            margin-left: 20px;
            margin-right: 20px;
            margin-top: 5px;
        }

        p {
            margin: 5px
        }

        .barcodecell {
            text-align: center;
            vertical-align: middle;
        }
    </style>
</head>

<body>

    <p style="text-align: center;font-weight: bold;font-size: 20pt;">BEE CONNECT</p>

    <p style="text-align: center;font-size: 12pt;">77877877</p>
    <br>
    <!-- 
    <p style="font-size: 12pt;font-weight: bold;">ເລກບິນ :
        {{ $id }}
    </p> -->

    <div class="barcodecell"><barcode code="{{ $id }}" type="C128B" height="2" size="0.7" vertical-align /></div>
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