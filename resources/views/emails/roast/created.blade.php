<html>
    <head></head>
    <body>
        <p>Hello {{$payment->name}}!</p>

        <p>You can access your website roast at the following link: <a href="{{route('payments.show', $payment)}}">{{route('payments.show', $payment)}}</a></p>

        <p>– Oliver</p>
    </body>
</html>
