import express from "express";

import axios from "axios";


const app = express();


app.get("/call-php-api", async (req, res) => {

    try {

        const response =
            await axios.get(
                "http://localhost/shopping_cart/php_api/students.php"
            );


        res.json({

            success: true,

            phpApiData:
                response.data

        });

    }

    catch (error) {

        res.status(500).json({

            success: false,

            message:
                "PHP API call failed",

            error:
                error.message

        });

    }

});


app.listen(4000, () => {

    console.log(
        "Express client running on port 4000"
    );

});