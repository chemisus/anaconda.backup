<?xml version="1.0" ?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" />
    
    <xsl:template match="/">
        <html>
            <head>
                <title>asdf</title>
                <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
                <style type="text/css">
                    h3 + div {
                    }
                    
                    .module {
                        background-color: #cccccc;
                    }
                </style>
                <script type="text/javascript">
                    $('h3').live('click', function () {
                        $(this).next('div').toggle('slow');
                    });
                </script>
            </head>
            <body>
                <form method="get">
                    <xsl:apply-templates select="./*" />
                </form>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
