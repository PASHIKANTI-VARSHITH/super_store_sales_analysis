<?php
include 'connection.php';

//sql queries for dashboard
$sales_query = "SELECT ROUND(SUM(Sales), 3) AS total_sales FROM superstore_data";
$profit_query = "SELECT ROUND(SUM(Profit), 3) AS total_profit FROM superstore_data";
$orders_query = "SELECT COUNT(`Order ID`) AS total_orders FROM superstore_data";
$customers_query = "SELECT COUNT(DISTINCT `Customer ID`) AS total_customers FROM superstore_data";


$sales_result = $conn->query($sales_query);
$profit_result = $conn->query($profit_query);
$orders_result = $conn->query($orders_query);
$customers_result = $conn->query($customers_query);

//datafetching
$sales_data = $sales_result->fetch_assoc();
$profit_data = $profit_result->fetch_assoc();
$orders_data = $orders_result->fetch_assoc();
$customers_data = $customers_result->fetch_assoc();

//all orders data
$table_query = "SELECT * FROM superstore_data"; 
$table_result = $conn->query($table_query);

//fetching products with sales greater than $5000
$products_query = " SELECT `Product ID`, `Product Name`, `Category`, SUM(`Sales`) AS total_sales FROM superstore_data GROUP BY `Product ID`, `Product Name`, `Category` HAVING total_sales > 5000 ";
$products_result = $conn->query($products_query);

//query to get average discount by product category
$avgdiscount = "SELECT Category, AVG(Discount) AS Avg_Discount FROM superstore_data GROUP BY Category";
$avgdiscountresult = $conn->query($avgdiscount);

//query to get the top sales per customer
$topsalespercustomerquery = "SELECT `Customer ID`, `Customer Name`, SUM(Sales) AS Total_Sales FROM superstore_data GROUP BY `Customer ID`, `Customer Name` ORDER BY Total_Sales DESC LIMIT 5";
$topsalespercustomeresult = $conn->query($topsalespercustomerquery);

//query to get the top five cities
$topfivecitiessql = "SELECT `City`, SUM(Sales) AS City_Total_Sales FROM superstore_data GROUP BY `City` ORDER BY City_Total_Sales DESC LIMIT 5";
$topfivecitiesresult = $conn->query($topfivecitiessql);


//shipping mode sql
$shipping_modes = [];
$shipping_counts = [];

// Query to get the count of each shipping mode for the chart
$shipping_mode_query = "SELECT `Ship Mode`, COUNT(*) AS mode_count FROM superstore_data GROUP BY `Ship Mode`";
$shipping_mode_result = $conn->query($shipping_mode_query);

// Fetch shipping mode data for chart
while ($row = $shipping_mode_result->fetch_assoc()) {
    $shipping_modes[] = $row['Ship Mode']; 
    $shipping_counts[] = $row['mode_count']; 
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextGen Super Store</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="adminhome.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    
</head>
<body>

<header>
    <nav class="navbar">
        <div class="logo">NextGen Super Store</div>
        <div class="logout">
            <a href="landingpage.php"><i class='bx bx-log-out'></i>Logout</a>
        </div>
    </nav>
</header>

<div class="container">
    <div class="left">
        <div class="categories">
            <div class="category" onclick="showdashboard()">
                <h1><i class='bx bx-home-alt-2'></i>Dashboard</h1>
            </div>
            <div class="category" onclick="showproductsmore()">
                <h1>Products with Sales More than $5000</h1>
            </div>
            <div class="category" onclick="showavgdiscount()">
                <h1>Average Discounts on Products</h1>
            </div>
            <div class="category" onclick="showtopsalespercustomer()">
                <h1>Top customers</h1>
            </div>
            <div class="category" onclick="showtopfivecities()">
                <h1>Top five cities</h1>
            </div>
            <div class="category" onclick="showshippingmodes()">
                <h1>Shipping Modes</h1>
            </div>
        </div>
    </div>
    
    <div class="right">
        <!-- Dashboard Section -->
        <div id="dashboardSection" class="section" style="display: block;">
            <div class="cards">
                <div class="card">
                    <div class="details">
                        <h3><?php echo number_format($sales_data['total_sales'], 3); ?></h3>
                        <h3>Sales</h3>
                    </div>
                    <div class="icon">
                        <i class='bx bx-package'></i>
                    </div>
                </div>
                <div class="card">
                    <div class="details">
                        <h1><?php echo $orders_data['total_orders']; ?></h1>
                        <h3>Orders</h3>
                    </div>
                    <div class="icon">
                        <i class='bx bx-cart'></i>
                    </div>
                </div>
                <div class="card">
                    <div class="details">
                        <h3><?php echo $profit_data['total_profit']; ?></h3>
                        <h3>Profits</h3>
                    </div>
                    <div class="icon">
                        <i class='bx bx-bar-chart-alt-2'></i>
                    </div>
                </div>
                <div class="card">
                    <div class="details">
                        <h1><?php echo $customers_data['total_customers']; ?></h1>
                        <h3>Customers</h3>
                    </div>
                    <div class="icon">
                        <i class='bx bx-group'></i>
                    </div>
                </div>
            </div>
            <div class="all-table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Row ID</th>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Ship Date</th>
                            <th>Ship Mode</th>
                            <th>Customer ID</th>
                            <th>Customer Name</th>
                            <th>Segment</th>
                            <th>Country</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Postal Code</th>
                            <th>Region</th>
                            <th>Product ID</th>
                            <th>Category</th>
                            <th>Sub-Category</th>
                            <th>Product Name</th>
                            <th>Sales</th>
                            <th>Quantity</th>
                            <th>Discount</th>
                            <th>Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($table_result->num_rows > 0) {
                            while($row = $table_result->fetch_assoc()) {
                                echo "<tr>
                                    <td>" . $row['Row ID'] . "</td>
                                        <td>" . $row['Order ID'] . "</td>
                                        <td>" . $row['Order Date'] . "</td>
                                        <td>" . $row['Ship Date'] . "</td>
                                        <td>" . $row['Ship Mode'] . "</td>
                                        <td>" . $row['Customer ID'] . "</td>
                                        <td>" . $row['Customer Name'] . "</td>
                                        <td>" . $row['Segment'] . "</td>
                                        <td>" . $row['Country'] . "</td>
                                        <td>" . $row['City'] . "</td>
                                        <td>" . $row['State'] . "</td>
                                        <td>" . $row['Postal Code'] . "</td>
                                        <td>" . $row['Region'] . "</td>
                                        <td>" . $row['Product ID'] . "</td>
                                        <td>" . $row['Category'] . "</td>
                                        <td>" . $row['Sub-Category'] . "</td>
                                        <td>" . $row['Product Name'] . "</td>
                                        <td>" . $row['Sales'] . "</td>
                                        <td>" . $row['Quantity'] . "</td>
                                        <td>" . $row['Discount'] . "</td>
                                        <td>" . $row['Profit'] . "</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='20'>No data available</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>


        <!-- Products with Sales More than $5000 Section -->
        <div id="productsSection" class="section" style="display: none;">
            <h1>Products with Sales More than $5000</h1>
            <table class="p-table-container">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Total Sales</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($products_result->num_rows > 0) {
                        // Output data of each row
                        while($row = $products_result->fetch_assoc()) {
                            echo "<tr>
                                <td>" . $row['Product ID'] . "</td>
                                <td>" . $row['Product Name'] . "</td>
                                <td>" . $row['Category'] . "</td>
                                <td>" . $row['total_sales'] . "</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No data available</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Average Discount by Product Category Section -->
        <div id="avgdiscountSection" class="section" style="display: none;">
            <h1>Average Discount by Product Category</h1>
            <table class="table-container">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Average Discount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($avgdiscountresult->num_rows > 0) {
                        // Output data of each row
                        while($row = $avgdiscountresult->fetch_assoc()) {
                            echo "<tr>
                                <td>" . $row['Category'] . "</td>
                                <td>" . $row['Avg_Discount'] . "</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No data available</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Top Sales per Customer Section -->
        <div id="topsalespercustomerSection" class="section" style="display: none;">
            <h1>Top Sales per Customer</h1>
            <table class="table-container">
                <thead>
                    <tr>
                        <th>Customer ID</th>
                        <th>Customer Name</th>
                        <th>Total Sales</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($topsalespercustomeresult->num_rows > 0) {
                        // Output data of each row
                        while($row = $topsalespercustomeresult->fetch_assoc()) {
                            echo "<tr>
                                <td>" . $row['Customer ID'] . "</td>
                                <td>" . $row['Customer Name'] . "</td>
                                <td>" . $row['Total_Sales'] . "</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No data available</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            
        </div>

        <!-- Top Five Cities Section -->
        <div id="topfivecitiesSection" class="section" style="display: none;">
            <h1>Top Five Cities by Total Sales</h1>
            <div class="cities-container">
                <!-- Table Container -->
                <div >
                    <h2>Sales Data</h2>
                    <table class="table-container">
                        <thead>
                            <tr>
                                <th>City</th>
                                <th>Total Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($topfivecitiesresult->num_rows > 0) {
                                while($row = $topfivecitiesresult->fetch_assoc()) {
                                    echo "<tr>
                                        <td>" . $row['City'] . "</td>
                                        <td>" . $row['City_Total_Sales'] . "</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='2'>No data available</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add the shipping chart section -->
    <div id="shippingmodesection"  class="section shippingmode" style="display: none;">
        <h2>Distribution of Shipping Modes</h2>
        <div class="chart-container">
            <canvas id="shippingModeChart"></canvas>
        </div>
    </div>
    </div>
</div>

<script>
    function showdashboard() {
        document.getElementById('dashboardSection').style.display = 'block';
        document.getElementById('productsSection').style.display = 'none';
        document.getElementById('avgdiscountSection').style.display = 'none';
        document.getElementById('topsalespercustomerSection').style.display = 'none';
        document.getElementById('topfivecitiesSection').style.display = 'none';
        document.getElementById('shippingmodesection').style.display = 'none';
    }
    function showproductsmore() {
        document.getElementById('dashboardSection').style.display = 'none';
        document.getElementById('productsSection').style.display = 'block';
        document.getElementById('avgdiscountSection').style.display = 'none';
        document.getElementById('topsalespercustomerSection').style.display = 'none';
        document.getElementById('topfivecitiesSection').style.display = 'none';
        document.getElementById('shippingmodesection').style.display = 'none';
    }
    function showavgdiscount() {
        document.getElementById('dashboardSection').style.display = 'none';
        document.getElementById('productsSection').style.display = 'none';
        document.getElementById('avgdiscountSection').style.display = 'block';
        document.getElementById('topsalespercustomerSection').style.display = 'none';
        document.getElementById('topfivecitiesSection').style.display = 'none';
        document.getElementById('shippingmodesection').style.display = 'none';
    }
    function showtopsalespercustomer() {
        document.getElementById('dashboardSection').style.display = 'none';
        document.getElementById('productsSection').style.display = 'none';
        document.getElementById('avgdiscountSection').style.display = 'none';
        document.getElementById('topsalespercustomerSection').style.display = 'block';
        document.getElementById('topfivecitiesSection').style.display = 'none';
        document.getElementById('shippingmodesection').style.display = 'none';
    }
    function showtopfivecities() {
        document.getElementById('dashboardSection').style.display = 'none';
        document.getElementById('productsSection').style.display = 'none';
        document.getElementById('avgdiscountSection').style.display = 'none';
        document.getElementById('topsalespercustomerSection').style.display = 'none';
        document.getElementById('shippingmodesection').style.display = 'none';
        document.getElementById('topfivecitiesSection').style.display = 'block';
    }
    function showshippingmodes() {
        document.getElementById('dashboardSection').style.display = 'none';
        document.getElementById('productsSection').style.display = 'none';
        document.getElementById('avgdiscountSection').style.display = 'none';
        document.getElementById('topsalespercustomerSection').style.display = 'none';
        document.getElementById('shippingmodesection').style.display = 'block';
        document.getElementById('topfivecitiesSection').style.display = 'none';
    }
</script>
<script>
        var shippingModes = <?php echo json_encode($shipping_modes); ?>;
        var shippingCounts = <?php echo json_encode($shipping_counts); ?>;

        function generateRandomColors(count) {
            var colors = [];
            for (var i = 0; i < count; i++) {
                colors.push('hsl(' + Math.random() * 360 + ', 100%, 75%)');
            }
            return colors;
        }

        // Creating the pie chart
        var ctx = document.getElementById('shippingModeChart').getContext('2d');
        var shippingModeChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: shippingModes,
                datasets: [{
                    label: 'Shipping Mode Distribution',
                    data: shippingCounts,
                    backgroundColor: generateRandomColors(shippingModes.length),
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            }
        });
    </script>


</body>
</html>
