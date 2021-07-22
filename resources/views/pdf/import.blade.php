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

    <p style="font-size: 12pt;font-weight: bold;">ເລກບິນ :
        {{ $id }}
    </p>

    <p style="font-size: 11pt;">ວັນທີ :
        {{ $date }}
    </p>

    <p style="font-size: 11pt;">ເຖິງສາຂາ :
        {{ $to }}
    </p>

    <p style="font-size: 11pt;">ນ້ຳໜັກລວມ :
        {{ $weight_kg }} kg
    </p>


    @if ($fee)
        <p style="font-size: 11pt;">ຄ່າຂົນສົ່ງ :
            {{ number_format($fee) }} ກີບ
        </p>
    @endif
    @if ($pack_price)
        <p style="font-size: 11pt;">ຄ່າເປົາ :
            {{ number_format($pack_price) }} ກີບ
        </p>
    @endif
    @if ($service_charge)
        <p style="font-size: 11pt;">ຄ່າບໍລິການເພີ່ມເຕີມ :
            {{ number_format($service_charge) }} ກີບ
        </p>
    @endif


    <hr>
    <p style="font-size: 12pt;">ລວມເປັນເງິນ :
        {{ number_format($price) }} ກີບ
    </p>

</body>

</html>
