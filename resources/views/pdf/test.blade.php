<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>

    <p style="text-align: center;font-weight: bold;">ໃບນຳສົ່ງສິນຄ້າ</p>

    <p>ລະຫັດເຄື່ອງ :
        <?php echo $id; ?>
    </p>
    <p>ຂະໜາດ :
        <?php echo $weight; ?>
        <?php echo $weight_type === 'gram' ? 'ກຼາມ' : 'ແມັດກ້ອນ'; ?>
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
    <p>ລາຄາ :
        <?php echo $price; ?> ກີບ
    </p>

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
