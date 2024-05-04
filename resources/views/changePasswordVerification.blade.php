<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-AU-Compatible" content="ie-edge">
    <title>Change Password</title>
</head>

<body>
    <p>
        Halo <b>{{ $details['username']}}</b>
    </p>

    <p>
        Use the verify code bellow to change your password
    </p>

    <p>
        Berikut adalah akun anda:
    </p>

    <table>
        <tr>
            <td>Username</td>
            <td>:</td>
            <td>{{$details['username']}}</td>
        </tr>
        <tr>
            <td>Website</td>
            <td>:</td>
            <td>{{$details['website']}}</td>
        </tr>
        <tr>
            <td>Update Date </td>
            <td>:</td>
            <td>{{$details['dateTime']}}</td>
        </tr>
    </table>

    <center>
        <h3>
            Verification Code
        </h3>

        <h1>
            <b>{{$details['verification_code']}}</b>
        </h1>
    </center>

</body>

</html>