import express from "express";

const app = express();

app.use(express.json());


app.get("/api/products", (req, res) => {

    const products = [

        {
            id: 1,
            pname: "T-Shirt",
            price: 499
        },

        {
            id: 2,
            pname: "Laptop",
            price: 55000
        }

    ];


    res.json({

        success: true,

        products: products

    });

});


app.get("/api/student/:id", (req, res) => {

    res.json({

        success: true,

        student: {

            id: req.params.id,

            name: "Rahul",

            course: "BCA"

        }

    });

});


app.listen(3000, () => {

    console.log(
        "Express server running on port 3000"
    );

});