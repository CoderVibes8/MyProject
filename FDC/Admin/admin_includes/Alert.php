<?php
    if (isset($_SESSION['message']) && !empty($_SESSION['message'])):
?>
    <script>
        const messageData = <?php echo json_encode($_SESSION["message"]); ?>;
        Swal.fire({
            title: messageData.title,
            text: messageData.message,
            icon: messageData.type,
            shownConfirmButton: false,
            timer: 3000
        });
    </script>
<?php
    unset($_SESSION['message']);
    endif;
?>