# check if file Constant.php exist
if [ -f "./Helper/App/Constant.php" ]
then
    echo "Constant exist"
    exit 0
else
    echo "Constant does not exist"
    echo "Creating Constant"
    cp "./Helper/App/Constant_example.php" "./Helper/App/Constant.php"
fi

# ask user to input database name
echo "Active debugging mode? (y/n)"
read debug
echo "Please enter your database host"
read dbhost
echo "Please enter your database name"
read dbname
echo "Please enter your database user"
read dbuser
echo "Please enter your database password"
read dbpass

# replace Constant_Example with Constant
sed -i "s/Constant_Example/Constant/g" "./Helper/App/Constant.php"

# replace 'debugvalue' (with ') by true/false
if [ "$debug" = "y" ]
then
    sed -i "s/'debugvalue'/true/g" "./Helper/App/Constant.php"
else
    sed -i "s/'debugvalue'/false/g" "./Helper/App/Constant.php"
fi

# replace database name in Constant.php
sed -i "s/localhost/$dbhost/g" "./Helper/App/Constant.php"
sed -i "s/yourdbname/$dbname/g" "./Helper/App/Constant.php"
sed -i "s/yourdbuser/$dbuser/g" "./Helper/App/Constant.php"
sed -i "s/yourdbpass/$dbpass/g" "./Helper/App/Constant.php"
