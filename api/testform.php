<html>
    <head>
        
    </head>
    <body>
        <h1>API TESTER</h1>
        <table>
            <tr>
                <td style="width:800px">
                <form method="post" action="index.php" target="i_api">
                <br>c
                <br><input type="text" name="c" value="" placeholder="Class Name">
                <br><br>m
                <br><input type="text" name="m" value="" placeholder="Function Name">
                <br><br>data
                <br>
                <textarea style="width:600px" name="data" placeholder="Json Parameters"></textarea>


                <br><input type="submit" value="Go!">
                </form>
                </td>
            </tr>
            <tr>
                <td>
                    <iframe name="i_api" style="width:100%;height:200px"></iframe>
                </td>
            </tr>
        </table>
    </body>
</html>