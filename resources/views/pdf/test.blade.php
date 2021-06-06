<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style>
        @page {
            margin: 20px;
        }

        p {
            margin: 7px;
            font-size: 10pt;
        }

    </style>

</head>

<body>

    <p style="text-align: center;font-weight: bold;font-size: 12pt">ໃບນຳສົ່ງສິນຄ້າພາຍໃນ</p>

    <p style="font-weight: bold">ລະຫັດເຄື່ອງ :
        <?php echo $id; ?>
    </p>
    <p>ຂະໜາດ :
        <?php echo $weight; ?>
        <?php echo $weight_type === 'kg' ? 'kg' : 'ແມັດກ້ອນ'; ?>
    </p>
    <p>ສົ່ງວັນທີ :
        <?php echo $date; ?>
    </p>

    <p>ຈາກສາຂາ :
        <?php echo $from; ?>
    </p>
    <p>ເຖິງສາຂາ :
        <?php echo $to; ?>
    </p>
    <p style="font-weight: bold">ລາຄາ :
        <?php echo $price; ?> ກີບ
    </p>

    <hr style="margin: 0px">

    <p>ຊື່ລູກຄ້າຜູ້ສົ່ງ :
        <?php echo $cust_send_name; ?>
    </p>
    <p>ເບີໂທຜູ້ສົ່ງ :
        <?php echo $cust_send_tel; ?>
    </p>
    <p>ຊື່ລູກຄ້າຜູ້ຮັບ :
        <?php echo $cust_receiver_name; ?>
    </p>
    <p>ເບີໂທຜູ້ຮັບ :
        <?php echo $cust_receiver_tel; ?>
    </p>

    <barcode code="{{ $id }}" type="C128B" height="1" text="2" />

</body>

</html>
