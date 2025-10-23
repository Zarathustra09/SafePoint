<?php

namespace Database\Seeders;

use App\Models\CrimeReport;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CrimeReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Base coordinates for Tanauan City, Batangas
        // Adjusted slightly for each barangay and road

        $crimes = [
            // ALTURA MATANDA
            ['barangay' => 'ALTURA MATANDA', 'road' => 'Altura Main Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.137847, 'lng' => 121.076988],
            ['barangay' => 'ALTURA MATANDA', 'road' => 'Altura East Street', 'crime' => 'Violence against women and children (VAWC)', 'lat' => 14.135612, 'lng' => 121.084907],

            // AMBULONG
            ['barangay' => 'AMBULONG', 'road' => 'Ambulong-Tanauan Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0945, 'lng' => 121.1523],
            ['barangay' => 'AMBULONG', 'road' => 'Ambulong Junction', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0952, 'lng' => 121.1530],
            ['barangay' => 'AMBULONG', 'road' => 'Ambulong Central Street', 'crime' => 'Rape / Sexual assault', 'lat' => 14.0938, 'lng' => 121.1517],
            ['barangay' => 'AMBULONG', 'road' => 'Ambulong South Road', 'crime' => 'Violence against women and children (VAWC)', 'lat' => 14.0940, 'lng' => 121.1528],
            ['barangay' => 'AMBULONG', 'road' => 'Ambulong West Avenue', 'crime' => 'Theft / Snatching', 'lat' => 14.0947, 'lng' => 121.1520],

            // BAGBAG
            ['barangay' => 'BAGBAG', 'road' => 'Bagbag-Tanauan Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0812, 'lng' => 121.1445],

            // BAGUMBAYAN
            ['barangay' => 'BAGUMBAYAN', 'road' => 'Bagumbayan Main Street', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0923, 'lng' => 121.1556],
            ['barangay' => 'BAGUMBAYAN', 'road' => 'Bagumbayan Road 1', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0929, 'lng' => 121.1562],
            ['barangay' => 'BAGUMBAYAN', 'road' => 'Bagumbayan Road 2', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0916, 'lng' => 121.1549],
            ['barangay' => 'BAGUMBAYAN', 'road' => 'Bagumbayan East Street', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0925, 'lng' => 121.1558],
            ['barangay' => 'BAGUMBAYAN', 'road' => 'Bagumbayan Central Avenue', 'crime' => 'Child abuse / exploitation', 'lat' => 14.0920, 'lng' => 121.1553],

            // BALELE
            ['barangay' => 'BALELE', 'road' => 'Balele Highway', 'crime' => 'Rape / Sexual assault', 'lat' => 14.0789, 'lng' => 121.1423],

            // BANADERO
            ['barangay' => 'BANADERO', 'road' => 'Banadero Main Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0867, 'lng' => 121.1612],
            ['barangay' => 'BANADERO', 'road' => 'Banadero Street 1', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0873, 'lng' => 121.1618],
            ['barangay' => 'BANADERO', 'road' => 'Banadero North Road', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0879, 'lng' => 121.1606],
            ['barangay' => 'BANADERO', 'road' => 'Banadero Central Avenue', 'crime' => 'Child abuse / exploitation', 'lat' => 14.0870, 'lng' => 121.1609],

            // BANJO EAST
            ['barangay' => 'BANJO EAST', 'road' => 'Banjo East Main Road', 'crime' => 'Theft / Snatching', 'lat' => 14.0901, 'lng' => 121.1489],
            ['barangay' => 'BANJO EAST', 'road' => 'Banjo East Road', 'crime' => 'Theft / Snatching', 'lat' => 14.0908, 'lng' => 121.1495],

            // BILOG-BILOG
            ['barangay' => 'BILOG-BILOG', 'road' => 'Bilog-Bilog Main Street', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0823, 'lng' => 121.1534],
            ['barangay' => 'BILOG-BILOG', 'road' => 'Bilog-Bilog Road 1', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0829, 'lng' => 121.1540],
            ['barangay' => 'BILOG-BILOG', 'road' => 'Bilog-Bilog Road 2', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0816, 'lng' => 121.1527],
            ['barangay' => 'BILOG-BILOG', 'road' => 'Bilog-Bilog Central Avenue', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0820, 'lng' => 121.1537],

            // BOOT
            ['barangay' => 'BOOT', 'road' => 'Boot Main Road', 'crime' => 'Rape / Sexual assault', 'lat' => 14.0745, 'lng' => 121.1398],
            ['barangay' => 'BOOT', 'road' => 'Boot East Street', 'crime' => 'Child abuse / exploitation', 'lat' => 14.0751, 'lng' => 121.1404],

            // CALE
            ['barangay' => 'CALE', 'road' => 'Cale-Tanauan Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0778, 'lng' => 121.1567],
            ['barangay' => 'CALE', 'road' => 'Cale Main Street', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0784, 'lng' => 121.1573],
            ['barangay' => 'CALE', 'road' => 'Cale South Road', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0771, 'lng' => 121.1560],

            // DARASA
            ['barangay' => 'DARASA', 'road' => 'Darasa National Road', 'crime' => 'Physical injuries / Assault', 'lat' => 14.0889, 'lng' => 121.1623],
            ['barangay' => 'DARASA', 'road' => 'Darasa Main Street', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0895, 'lng' => 121.1629],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 1', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0882, 'lng' => 121.1616],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 2', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0891, 'lng' => 121.1626],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 3', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0886, 'lng' => 121.1620],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 4', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0898, 'lng' => 121.1632],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 5', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0879, 'lng' => 121.1613],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 6', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0893, 'lng' => 121.1625],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 7', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0884, 'lng' => 121.1618],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 8', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0897, 'lng' => 121.1631],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 9', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0880, 'lng' => 121.1614],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 10', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0894, 'lng' => 121.1628],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 11', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0887, 'lng' => 121.1621],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 12', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0896, 'lng' => 121.1630],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 13', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0881, 'lng' => 121.1615],
            ['barangay' => 'DARASA', 'road' => 'Darasa East Avenue', 'crime' => 'Carnapping', 'lat' => 14.0890, 'lng' => 121.1624],
            ['barangay' => 'DARASA', 'road' => 'Darasa West Street', 'crime' => 'Theft / Snatching', 'lat' => 14.0883, 'lng' => 121.1617],
            ['barangay' => 'DARASA', 'road' => 'Darasa Central Road', 'crime' => 'Theft / Snatching', 'lat' => 14.0892, 'lng' => 121.1627],
            ['barangay' => 'DARASA', 'road' => 'Darasa North Street', 'crime' => 'Drunk and disorderly conduct (or Public scandal / Grave scandal / Grave coercion)', 'lat' => 14.0899, 'lng' => 121.1633],
            ['barangay' => 'DARASA', 'road' => 'Darasa South Avenue', 'crime' => 'Drunk and disorderly conduct (or Public scandal / Grave scandal / Grave coercion)', 'lat' => 14.0878, 'lng' => 121.1612],

            // JANOPOL ORIENTAL
            ['barangay' => 'JANOPOL ORIENTAL', 'road' => 'Janopol Main Road', 'crime' => 'Theft / Snatching', 'lat' => 14.0734, 'lng' => 121.1489],
            ['barangay' => 'JANOPOL ORIENTAL', 'road' => 'Janopol East Street', 'crime' => 'Theft / Snatching', 'lat' => 14.0740, 'lng' => 121.1495],

            // MABINI
            ['barangay' => 'MABINI', 'road' => 'Mabini Street', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0856, 'lng' => 121.1578],

            // MALAKING PULO
            ['barangay' => 'MALAKING PULO', 'road' => 'Malaking Pulo Road', 'crime' => 'Child abuse / exploitation', 'lat' => 14.0823, 'lng' => 121.1601],

            // MARIA PAZ
            ['barangay' => 'MARIA PAZ', 'road' => 'Maria Paz Avenue', 'crime' => 'Rape / Sexual assault', 'lat' => 14.0912, 'lng' => 121.1645],

            // MAUGAT
            ['barangay' => 'MAUGAT', 'road' => 'Maugat Main Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0945, 'lng' => 121.1678],
            ['barangay' => 'MAUGAT', 'road' => 'Maugat East Street', 'crime' => 'Child abuse / exploitation', 'lat' => 14.0951, 'lng' => 121.1684],

            // MONTANA (IK-IK)
            ['barangay' => 'MONTANA (IK-IK)', 'road' => 'Montana Main Road', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0967, 'lng' => 121.1712],

            // NATATAS
            ['barangay' => 'NATATAS', 'road' => 'Natatas Main Street', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0789, 'lng' => 121.1645],
            ['barangay' => 'NATATAS', 'road' => 'Natatas Road 1', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0795, 'lng' => 121.1651],
            ['barangay' => 'NATATAS', 'road' => 'Natatas Road 2', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0782, 'lng' => 121.1638],
            ['barangay' => 'NATATAS', 'road' => 'Natatas Central Avenue', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0786, 'lng' => 121.1648],
            ['barangay' => 'NATATAS', 'road' => 'Natatas East Road', 'crime' => 'Illegal possession of firearms', 'lat' => 14.0792, 'lng' => 121.1654],
            ['barangay' => 'NATATAS', 'road' => 'Natatas West Street', 'crime' => 'Bribery / Corruption (or Election-related offense)', 'lat' => 14.0779, 'lng' => 121.1635],
            ['barangay' => 'NATATAS', 'road' => 'Natatas South Avenue', 'crime' => 'Theft / Snatching', 'lat' => 14.0783, 'lng' => 121.1641],
            ['barangay' => 'NATATAS', 'road' => 'Natatas North Road', 'crime' => 'Child abuse / exploitation', 'lat' => 14.0798, 'lng' => 121.1657],

            // PAGASPAS
            ['barangay' => 'PAGASPAS', 'road' => 'Pagaspas Main Road', 'crime' => 'Illegal possession of firearms', 'lat' => 14.0923, 'lng' => 121.1701],

            // PANTAY BATA
            ['barangay' => 'PANTAY BATA', 'road' => 'Pantay Bata Main Street', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0856, 'lng' => 121.1667],
            ['barangay' => 'PANTAY BATA', 'road' => 'Pantay Bata Central Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0862, 'lng' => 121.1673],

            // PANTAY MATANDA
            ['barangay' => 'PANTAY MATANDA', 'road' => 'Pantay Matanda Highway', 'crime' => 'Sexual offenses (rape, harassment)', 'lat' => 14.0878, 'lng' => 121.1689],

            // POBLACION BARANGAY 1
            ['barangay' => 'POBLACION BARANGAY 1', 'road' => 'Poblacion 1 Main Street', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0845, 'lng' => 121.1501],
            ['barangay' => 'POBLACION BARANGAY 1', 'road' => 'Poblacion 1 Road 1', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0851, 'lng' => 121.1507],
            ['barangay' => 'POBLACION BARANGAY 1', 'road' => 'Poblacion 1 Road 2', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0838, 'lng' => 121.1494],
            ['barangay' => 'POBLACION BARANGAY 1', 'road' => 'Poblacion 1 Central Avenue', 'crime' => 'Illegal possession of firearms', 'lat' => 14.0842, 'lng' => 121.1504],

            // POBLACION BARANGAY 2
            ['barangay' => 'POBLACION BARANGAY 2', 'road' => 'Poblacion 2 Main Road', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0834, 'lng' => 121.1512],
            ['barangay' => 'POBLACION BARANGAY 2', 'road' => 'Poblacion 2 Road 1', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0840, 'lng' => 121.1518],
            ['barangay' => 'POBLACION BARANGAY 2', 'road' => 'Poblacion 2 Road 2', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0827, 'lng' => 121.1505],
            ['barangay' => 'POBLACION BARANGAY 2', 'road' => 'Poblacion 2 Road 3', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0831, 'lng' => 121.1515],
            ['barangay' => 'POBLACION BARANGAY 2', 'road' => 'Poblacion 2 Road 4', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0843, 'lng' => 121.1521],
            ['barangay' => 'POBLACION BARANGAY 2', 'road' => 'Poblacion 2 Road 5', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0824, 'lng' => 121.1502],
            ['barangay' => 'POBLACION BARANGAY 2', 'road' => 'Poblacion 2 Road 6', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0837, 'lng' => 121.1509],
            ['barangay' => 'POBLACION BARANGAY 2', 'road' => 'Poblacion 2 East Street', 'crime' => 'Theft / Snatching', 'lat' => 14.0846, 'lng' => 121.1524],
            ['barangay' => 'POBLACION BARANGAY 2', 'road' => 'Poblacion 2 West Avenue', 'crime' => 'Drunk and disorderly conduct (or Public scandal / Grave scandal / Grave coercion)', 'lat' => 14.0821, 'lng' => 121.1499],

            // POBLACION BARANGAY 3
            ['barangay' => 'POBLACION BARANGAY 3', 'road' => 'Poblacion 3 Main Street', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0812, 'lng' => 121.1523],
            ['barangay' => 'POBLACION BARANGAY 3', 'road' => 'Poblacion 3 Central Road', 'crime' => 'Violence against women and children (VAWC)', 'lat' => 14.0818, 'lng' => 121.1529],
            ['barangay' => 'POBLACION BARANGAY 3', 'road' => 'Poblacion 3 East Avenue', 'crime' => 'Violence against women and children (VAWC)', 'lat' => 14.0805, 'lng' => 121.1516],
            ['barangay' => 'POBLACION BARANGAY 3', 'road' => 'Poblacion 3 South Street', 'crime' => 'Theft / Snatching', 'lat' => 14.0809, 'lng' => 121.1526],

            // POBLACION BARANGAY 4
            ['barangay' => 'POBLACION BARANGAY 4', 'road' => 'Poblacion 4 Main Road', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0801, 'lng' => 121.1534],
            ['barangay' => 'POBLACION BARANGAY 4', 'road' => 'Poblacion 4 Central Street', 'crime' => 'Illegal possession (of firearms or similar weapons etc)', 'lat' => 14.0807, 'lng' => 121.1540],
            ['barangay' => 'POBLACION BARANGAY 4', 'road' => 'Poblacion 4 East Avenue', 'crime' => 'Bribery / Corruption (or Election-related offense)', 'lat' => 14.0794, 'lng' => 121.1527],
            ['barangay' => 'POBLACION BARANGAY 4', 'road' => 'Poblacion 4 West Road', 'crime' => 'Child abuse / exploitation', 'lat' => 14.0798, 'lng' => 121.1537],

            // POBLACION BARANGAY 5
            ['barangay' => 'POBLACION BARANGAY 5', 'road' => 'Poblacion 5 Main Street', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0789, 'lng' => 121.1545],
            ['barangay' => 'POBLACION BARANGAY 5', 'road' => 'Poblacion 5 Road 1', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0795, 'lng' => 121.1551],
            ['barangay' => 'POBLACION BARANGAY 5', 'road' => 'Poblacion 5 Road 2', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0782, 'lng' => 121.1538],
            ['barangay' => 'POBLACION BARANGAY 5', 'road' => 'Poblacion 5 Road 3', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0786, 'lng' => 121.1548],
            ['barangay' => 'POBLACION BARANGAY 5', 'road' => 'Poblacion 5 Road 4', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0798, 'lng' => 121.1554],
            ['barangay' => 'POBLACION BARANGAY 5', 'road' => 'Poblacion 5 Road 5', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0779, 'lng' => 121.1535],
            ['barangay' => 'POBLACION BARANGAY 5', 'road' => 'Poblacion 5 Road 6', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0792, 'lng' => 121.1542],

            // POBLACION BARANGAY 6
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 Main Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0778, 'lng' => 121.1556],
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 Road 1', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0784, 'lng' => 121.1562],
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 Central Street', 'crime' => 'Rape / Sexual assault', 'lat' => 14.0771, 'lng' => 121.1549],
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 Road 2', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0775, 'lng' => 121.1559],
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 Road 3', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0787, 'lng' => 121.1565],
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 Road 4', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0768, 'lng' => 121.1546],
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 Road 5', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0781, 'lng' => 121.1553],
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 East Avenue', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0790, 'lng' => 121.1568],
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 West Road', 'crime' => 'Public scandal / Grave Infamy + Grave coercion (or Minor harassment)', 'lat' => 14.0765, 'lng' => 121.1543],
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 North Street', 'crime' => 'Drunk and disorderly conduct (or Public scandal / Grave scandal / Grave coercion)', 'lat' => 14.0793, 'lng' => 121.1571],
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 South Avenue', 'crime' => 'Physical injuries / Assault', 'lat' => 14.0762, 'lng' => 121.1540],
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 Junction Road', 'crime' => 'FRUSTRATED Murder / Homicide', 'lat' => 14.0772, 'lng' => 121.1552],

            // POBLACION BARANGAY 7
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Main Street', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0867, 'lng' => 121.1545],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 1', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0873, 'lng' => 121.1551],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 2', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0860, 'lng' => 121.1538],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 3', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0864, 'lng' => 121.1548],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 4', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0876, 'lng' => 121.1554],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 5', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0857, 'lng' => 121.1535],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 6', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0870, 'lng' => 121.1542],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 7', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0879, 'lng' => 121.1557],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 8', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0854, 'lng' => 121.1532],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 9', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0861, 'lng' => 121.1539],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 10', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0882, 'lng' => 121.1560],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 11', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0851, 'lng' => 121.1529],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 12', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0867, 'lng' => 121.1545],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 East Street', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0885, 'lng' => 121.1563],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 West Avenue', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0848, 'lng' => 121.1526],

            // SAMBAT
            ['barangay' => 'SAMBAT', 'road' => 'Sambat Main Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0934, 'lng' => 121.1734],
            ['barangay' => 'SAMBAT', 'road' => 'Sambat Road 1', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0940, 'lng' => 121.1740],
            ['barangay' => 'SAMBAT', 'road' => 'Sambat Road 2', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0927, 'lng' => 121.1727],
            ['barangay' => 'SAMBAT', 'road' => 'Sambat Road 3', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0931, 'lng' => 121.1737],
            ['barangay' => 'SAMBAT', 'road' => 'Sambat Central Avenue', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0943, 'lng' => 121.1743],
            ['barangay' => 'SAMBAT', 'road' => 'Sambat East Street', 'crime' => 'Illegal possession of firearms', 'lat' => 14.0946, 'lng' => 121.1746],
            ['barangay' => 'SAMBAT', 'road' => 'Sambat West Road', 'crime' => 'Public scandal / Grave Infamy + Grave coercion (or Minor harassment)', 'lat' => 14.0924, 'lng' => 121.1724],
            ['barangay' => 'SAMBAT', 'road' => 'Sambat North Avenue', 'crime' => 'Bribery / Corruption (or Election-related offense)', 'lat' => 14.0949, 'lng' => 121.1749],
            ['barangay' => 'SAMBAT', 'road' => 'Sambat South Street', 'crime' => 'Theft / Snatching', 'lat' => 14.0921, 'lng' => 121.1721],

            // SANTOR
            ['barangay' => 'SANTOR', 'road' => 'Santor Main Road', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0812, 'lng' => 121.1689],
            ['barangay' => 'SANTOR', 'road' => 'Santor Central Street', 'crime' => 'Child abuse / exploitation', 'lat' => 14.0818, 'lng' => 121.1695],

            // SULPOC
            ['barangay' => 'SULPOC', 'road' => 'Sulpoc Main Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0756, 'lng' => 121.1712],
            ['barangay' => 'SULPOC', 'road' => 'Sulpoc Central Avenue', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0762, 'lng' => 121.1718],
            ['barangay' => 'SULPOC', 'road' => 'Sulpoc East Street', 'crime' => 'Bribery / Corruption (or Election-related offense)', 'lat' => 14.0749, 'lng' => 121.1705],

            // SUPLANG
            ['barangay' => 'SUPLANG', 'road' => 'Suplang Main Road', 'crime' => 'Child abuse / exploitation', 'lat' => 14.0689, 'lng' => 121.1678],

            // TALAGA
            ['barangay' => 'TALAGA', 'road' => 'Talaga Main Street', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0723, 'lng' => 121.1734],
            ['barangay' => 'TALAGA', 'road' => 'Talaga Central Road', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0729, 'lng' => 121.1740],
            ['barangay' => 'TALAGA', 'road' => 'Talaga East Avenue', 'crime' => 'Theft / Snatching', 'lat' => 14.0716, 'lng' => 121.1727],

            // TINURIK
            ['barangay' => 'TINURIK', 'road' => 'Tinurik Main Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0667, 'lng' => 121.1756],
            ['barangay' => 'TINURIK', 'road' => 'Tinurik Road 1', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0673, 'lng' => 121.1762],
            ['barangay' => 'TINURIK', 'road' => 'Tinurik Central Street', 'crime' => 'Rape / Sexual assault', 'lat' => 14.0660, 'lng' => 121.1749],
            ['barangay' => 'TINURIK', 'road' => 'Tinurik Road 2', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0664, 'lng' => 121.1759],
            ['barangay' => 'TINURIK', 'road' => 'Tinurik Road 3', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0676, 'lng' => 121.1765],
            ['barangay' => 'TINURIK', 'road' => 'Tinurik Road 4', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0657, 'lng' => 121.1746],
            ['barangay' => 'TINURIK', 'road' => 'Tinurik Road 5', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0670, 'lng' => 121.1753],
            ['barangay' => 'TINURIK', 'road' => 'Tinurik East Avenue', 'crime' => 'Illegal possession of firearms', 'lat' => 14.0679, 'lng' => 121.1768],
            ['barangay' => 'TINURIK', 'road' => 'Tinurik West Street', 'crime' => 'Bribery / Corruption (or Election-related offense)', 'lat' => 14.0654, 'lng' => 121.1743],
            ['barangay' => 'TINURIK', 'road' => 'Tinurik South Road', 'crime' => 'Drunk and disorderly conduct (or Public scandal / Grave scandal / Grave coercion)', 'lat' => 14.0651, 'lng' => 121.1740],

            // TRAPICHE
            ['barangay' => 'TRAPICHE', 'road' => 'Trapiche Main Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0634, 'lng' => 121.1789],
            ['barangay' => 'TRAPICHE', 'road' => 'Trapiche Central Street', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.0640, 'lng' => 121.1795],
            ['barangay' => 'TRAPICHE', 'road' => 'Trapiche East Avenue', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.0627, 'lng' => 121.1782],
        ];

        $severities = ['low', 'medium', 'high', 'critical'];
        $statuses = ['pending', 'under_investigation', 'resolved', 'closed'];

        foreach ($crimes as $crime) {
            CrimeReport::create([
                'title' => $crime['crime'],
                'description' => "Incident reported at {$crime['road']}, {$crime['barangay']}, Tanauan City, Batangas. This is a {$crime['crime']} case that requires appropriate action from law enforcement authorities.",
                'severity' => $severities[array_rand($severities)],
                'latitude' => $crime['lat'],
                'longitude' => $crime['lng'],
                'address' => "{$crime['road']}, {$crime['barangay']}, Tanauan City, Batangas",
                'status' => $statuses[array_rand($statuses)],
                'incident_date' => Carbon::now()->subDays(rand(1, 365)),
                'reported_by' => null, // Set to null or assign user IDs if you have users in your database
            ]);
        }

        $this->command->info('Crime reports seeded successfully!');
        $this->command->info('Total records created: ' . count($crimes));
    }
}
