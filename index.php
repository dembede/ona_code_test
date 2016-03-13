<!DOCTYPE html >
<html>
    <head>
        <title>Water Points Module</title>
    </head>
    <body style="font-family:'Courier'; font-size: 12px;">
        <h1>WATER POINTS MODULE</h1><hr/>
        <div class="content">
            <?php 
                
                include_once('modules/water_points_module.php');

                echo "<h3>PROCESSED DATA <em>(JSON)</em></h3>";
                echo json_encode($arr_processed_data);

                echo "<h3>PROCESSED DATA <em>(PHP)</em></h3>";
                print_r($arr_processed_data);

                echo "<hr/><h3>FILE OUTPUT <em>(JSON)</em></h3>";
                echo "Output file here » <a href='./modules/files/processed_data.json' target='_blank' title='Processed data URL'>processed_data.json</a>"
            ?>
        </div>
    </body>
</html>