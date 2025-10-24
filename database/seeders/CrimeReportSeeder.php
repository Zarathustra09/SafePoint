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
        $crimes = [
            // ALTURA MATANDA
            ['barangay' => 'ALTURA MATANDA', 'road' => 'Altura Main Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.135714, 'lng' => 121.080910],
            ['barangay' => 'ALTURA MATANDA', 'road' => 'Altura East Street', 'crime' => 'Violence against women and children (VAWC)', 'lat' => 14.134262, 'lng' => 121.084402],

            // AMBULONG
            ['barangay' => 'AMBULONG', 'road' => 'Ambulong-Tanauan Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.090139, 'lng' => 121.056546],
            ['barangay' => 'AMBULONG', 'road' => 'Ambulong Junction', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.090217, 'lng' => 121.058711],
            ['barangay' => 'AMBULONG', 'road' => 'Ambulong Central Street', 'crime' => 'Rape / Sexual assault', 'lat' => 14.092135, 'lng' => 121.057379],
            ['barangay' => 'AMBULONG', 'road' => 'Ambulong South Road', 'crime' => 'Violence against women and children (VAWC)', 'lat' => 14.086904, 'lng' => 121.058388],
            ['barangay' => 'AMBULONG', 'road' => 'Ambulong West Avenue', 'crime' => 'Theft / Snatching', 'lat' => 14.093412, 'lng' => 121.054670],

            // BAGBAG
            ['barangay' => 'BAGBAG', 'road' => 'Bagbag-Tanauan Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.696206, 'lng' => 121.032328],

            // BAGUMBAYAN
            ['barangay' => 'BAGUMBAYAN', 'road' => 'Bagumbayan Main Street', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.073013, 'lng' => 121.133860],
            ['barangay' => 'BAGUMBAYAN', 'road' => 'Bagumbayan Road 1', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.072035, 'lng' => 121.132752],
            ['barangay' => 'BAGUMBAYAN', 'road' => 'Bagumbayan Road 2', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.073229, 'lng' => 121.135509],
            ['barangay' => 'BAGUMBAYAN', 'road' => 'Bagumbayan East Street', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.078492, 'lng' => 121.141963],
            ['barangay' => 'BAGUMBAYAN', 'road' => 'Bagumbayan Central Avenue', 'crime' => 'Child abuse / exploitation', 'lat' => 14.072103, 'lng' => 121.137052],

            // BALELE
            ['barangay' => 'BALELE', 'road' => 'Balele Highway', 'crime' => 'Rape / Sexual assault', 'lat' => 14.064739, 'lng' => 121.093436],

            // BANADERO
            ['barangay' => 'BANADERO', 'road' => 'Banadero Main Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.083463, 'lng' => 121.068324],
            ['barangay' => 'BANADERO', 'road' => 'Banadero Street 1', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.084733, 'lng' => 121.065931],
            ['barangay' => 'BANADERO', 'road' => 'Banadero North Road', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.087668, 'lng' => 121.065923],
            ['barangay' => 'BANADERO', 'road' => 'Banadero Central Avenue', 'crime' => 'Child abuse / exploitation', 'lat' => 14.083638, 'lng' => 121.070880],

            // BANJO EAST
            ['barangay' => 'BANJO EAST', 'road' => 'Banjo East Main Road', 'crime' => 'Theft / Snatching', 'lat' => 14.056086, 'lng' => 121.140152],
            ['barangay' => 'BANJO EAST', 'road' => 'Banjo East Road', 'crime' => 'Theft / Snatching', 'lat' => 14.058789, 'lng' => 121.144136],

            // BILOG-BILOG
            ['barangay' => 'BILOG-BILOG', 'road' => 'Bilog-Bilog Main Street', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.108918, 'lng' => 121.083832],
            ['barangay' => 'BILOG-BILOG', 'road' => 'Bilog-Bilog Road 1', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.114981, 'lng' => 121.086844],
            ['barangay' => 'BILOG-BILOG', 'road' => 'Bilog-Bilog Road 2', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.107880, 'lng' => 121.092031],
            ['barangay' => 'BILOG-BILOG', 'road' => 'Bilog-Bilog Central Avenue', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.112617, 'lng' => 121.088707],

            // BOOT
            ['barangay' => 'BOOT', 'road' => 'Boot Main Road', 'crime' => 'Rape / Sexual assault', 'lat' => 14.045693, 'lng' => 121.077315],
            ['barangay' => 'BOOT', 'road' => 'Boot East Street', 'crime' => 'Child abuse / exploitation', 'lat' => 14.045655, 'lng' => 121.086723],

            // CALE
            ['barangay' => 'CALE', 'road' => 'Cale-Tanauan Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.119143, 'lng' => 121.096430],
            ['barangay' => 'CALE', 'road' => 'Cale Main Street', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.118679, 'lng' => 121.094998],
            ['barangay' => 'CALE', 'road' => 'Cale South Road', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.110835, 'lng' => 121.102237],

            // DARASA
            ['barangay' => 'DARASA', 'road' => 'Darasa National Road', 'crime' => 'Physical injuries / Assault', 'lat' => 14.071823, 'lng' => 121.151853],
            ['barangay' => 'DARASA', 'road' => 'Darasa Main Street', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.069079, 'lng' => 121.149868],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 1', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.069390, 'lng' => 121.152312],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 2', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.069569, 'lng' => 121.154558],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 3', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.067815, 'lng' => 121.154260],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 4', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.066119, 'lng' => 121.153189],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 5', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.063761, 'lng' => 121.154401],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 6', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.063134, 'lng' => 121.153723],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 7', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.062389, 'lng' => 121.152231],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 8', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.058821, 'lng' => 121.148725],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 9', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.059303, 'lng' => 121.148053],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 10', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.061239, 'lng' => 121.154163],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 11', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.061740, 'lng' => 121.155069],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 12', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.062626, 'lng' => 121.155678],
            ['barangay' => 'DARASA', 'road' => 'Darasa Road 13', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.068194, 'lng' => 121.157935],
            ['barangay' => 'DARASA', 'road' => 'Darasa East Avenue', 'crime' => 'Carnapping', 'lat' => 14.071658, 'lng' => 121.157811],
            ['barangay' => 'DARASA', 'road' => 'Darasa West Street', 'crime' => 'Theft / Snatching', 'lat' => 14.065603, 'lng' => 121.155568],
            ['barangay' => 'DARASA', 'road' => 'Darasa Central Road', 'crime' => 'Theft / Snatching', 'lat' => 14.071550, 'lng' => 121.153215],
            ['barangay' => 'DARASA', 'road' => 'Darasa North Street', 'crime' => 'Drunk and disorderly conduct (or Public scandal / Grave scandal / Grave coercion)', 'lat' => 14.062306, 'lng' => 121.157351],
            ['barangay' => 'DARASA', 'road' => 'Darasa South Avenue', 'crime' => 'Drunk and disorderly conduct (or Public scandal / Grave scandal / Grave coercion)', 'lat' => 14.059972, 'lng' => 121.154176],

            // JANOPOL ORIENTAL
            ['barangay' => 'JANOPOL ORIENTAL', 'road' => 'Janopol Main Road', 'crime' => 'Theft / Snatching', 'lat' => 14.084838, 'lng' => 121.093493],
            ['barangay' => 'JANOPOL ORIENTAL', 'road' => 'Janopol East Street', 'crime' => 'Theft / Snatching', 'lat' => 14.089145, 'lng' => 121.102468],

            // MABINI
            ['barangay' => 'MABINI', 'road' => 'Mabini Street', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.061317, 'lng' => 121.109695],

            // MALAKING PULO
            ['barangay' => 'MALAKING PULO', 'road' => 'Malaking Pulo Road', 'crime' => 'Child abuse / exploitation', 'lat' => 14.138366, 'lng' => 121.088654],

            // MARIA PAZ
            ['barangay' => 'MARIA PAZ', 'road' => 'Maria Paz Avenue', 'crime' => 'Rape / Sexual assault', 'lat' => 14.040819, 'lng' => 121.069884],

            // MAUGAT
            ['barangay' => 'MAUGAT', 'road' => 'Maugat Main Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.100255, 'lng' => 121.067124],
            ['barangay' => 'MAUGAT', 'road' => 'Maugat East Street', 'crime' => 'Child abuse / exploitation', 'lat' => 14.104226, 'lng' => 121.070554],

            // MONTANA (IK-IK)
            ['barangay' => 'MONTANA (IK-IK)', 'road' => 'Montana Main Road', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.126941, 'lng' => 121.061203],

            // NATATAS
            ['barangay' => 'NATATAS', 'road' => 'Natatas Main Street', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.075044, 'lng' => 121.106149],
            ['barangay' => 'NATATAS', 'road' => 'Natatas Road 1', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.073768, 'lng' => 121.105360],
            ['barangay' => 'NATATAS', 'road' => 'Natatas Road 2', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.073043, 'lng' => 121.108895],
            ['barangay' => 'NATATAS', 'road' => 'Natatas Central Avenue', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.074457, 'lng' => 121.109968],
            ['barangay' => 'NATATAS', 'road' => 'Natatas East Road', 'crime' => 'Illegal possession of firearms', 'lat' => 14.075575, 'lng' => 121.109362],
            ['barangay' => 'NATATAS', 'road' => 'Natatas West Street', 'crime' => 'Bribery / Corruption (or Election-related offense)', 'lat' => 14.083054, 'lng' => 121.124726],
            ['barangay' => 'NATATAS', 'road' => 'Natatas South Avenue', 'crime' => 'Theft / Snatching', 'lat' => 14.082092, 'lng' => 121.123435],
            ['barangay' => 'NATATAS', 'road' => 'Natatas North Road', 'crime' => 'Child abuse / exploitation', 'lat' => 14.082627, 'lng' => 121.121943],

            // PAGASPAS
            ['barangay' => 'PAGASPAS', 'road' => 'Pagaspas Main Road', 'crime' => 'Illegal possession of firearms', 'lat' => 14.103700, 'lng' => 121.132585],

            // PANTAY BATA
            ['barangay' => 'PANTAY BATA', 'road' => 'Pantay Bata Main Street', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.130691, 'lng' => 121.115874],
            ['barangay' => 'PANTAY BATA', 'road' => 'Pantay Bata Central Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.127334, 'lng' => 121.113363],

            // PANTAY MATANDA
            ['barangay' => 'PANTAY MATANDA', 'road' => 'Pantay Matanda Highway', 'crime' => 'Sexual offenses (rape, harassment)', 'lat' => 14.116050, 'lng' => 121.121859],

            // POBLACION BARANGAY 1
            ['barangay' => 'POBLACION BARANGAY 1', 'road' => 'Poblacion 1 Main Street', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.090495, 'lng' => 121.149148],
            ['barangay' => 'POBLACION BARANGAY 1', 'road' => 'Poblacion 1 Road 1', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.090061, 'lng' => 121.149726],
            ['barangay' => 'POBLACION BARANGAY 1', 'road' => 'Poblacion 1 Road 2', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.089638, 'lng' => 121.150568],
            ['barangay' => 'POBLACION BARANGAY 1', 'road' => 'Poblacion 1 Central Avenue', 'crime' => 'Illegal possession of firearms', 'lat' => 14.087195, 'lng' => 121.150199],

            // POBLACION BARANGAY 2
            ['barangay' => 'POBLACION BARANGAY 2', 'road' => 'Poblacion 2 Main Road', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.085424, 'lng' => 121.155686],
            ['barangay' => 'POBLACION BARANGAY 2', 'road' => 'Poblacion 2 Road 1', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.084743, 'lng' => 121.153158],
            ['barangay' => 'POBLACION BARANGAY 2', 'road' => 'Poblacion 2 Road 2', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.083766, 'lng' => 121.153804],
            ['barangay' => 'POBLACION BARANGAY 2', 'road' => 'Poblacion 2 Road 3', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.083877, 'lng' => 121.151295],
            ['barangay' => 'POBLACION BARANGAY 2', 'road' => 'Poblacion 2 Road 4', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.083323, 'lng' => 121.151869],
            ['barangay' => 'POBLACION BARANGAY 2', 'road' => 'Poblacion 2 Road 5', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.083431, 'lng' => 121.150715],
            ['barangay' => 'POBLACION BARANGAY 2', 'road' => 'Poblacion 2 Road 6', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.084268, 'lng' => 121.150347],
            ['barangay' => 'POBLACION BARANGAY 2', 'road' => 'Poblacion 2 East Street', 'crime' => 'Theft / Snatching', 'lat' => 14.082356, 'lng' => 121.150588],
            ['barangay' => 'POBLACION BARANGAY 2', 'road' => 'Poblacion 2 West Avenue', 'crime' => 'Drunk and disorderly conduct (or Public scandal / Grave scandal / Grave coercion)', 'lat' => 14.083776, 'lng' => 121.152026],

            // POBLACION BARANGAY 3
            ['barangay' => 'POBLACION BARANGAY 3', 'road' => 'Poblacion 3 Main Street', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.079649, 'lng' => 121.149696],
            ['barangay' => 'POBLACION BARANGAY 3', 'road' => 'Poblacion 3 Central Road', 'crime' => 'Violence against women and children (VAWC)', 'lat' => 14.077541, 'lng' => 121.150896],
            ['barangay' => 'POBLACION BARANGAY 3', 'road' => 'Poblacion 3 East Avenue', 'crime' => 'Violence against women and children (VAWC)', 'lat' => 14.077965, 'lng' => 121.151982],
            ['barangay' => 'POBLACION BARANGAY 3', 'road' => 'Poblacion 3 South Street', 'crime' => 'Theft / Snatching', 'lat' => 14.080860, 'lng' => 121.152129],

            // POBLACION BARANGAY 4
            ['barangay' => 'POBLACION BARANGAY 4', 'road' => 'Poblacion 4 Main Road', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.090830, 'lng' => 121.146500],
            ['barangay' => 'POBLACION BARANGAY 4', 'road' => 'Poblacion 4 Central Street', 'crime' => 'Illegal possession (of firearms or similar weapons etc)', 'lat' => 14.090417, 'lng' => 121.147983],
            ['barangay' => 'POBLACION BARANGAY 4', 'road' => 'Poblacion 4 East Avenue', 'crime' => 'Bribery / Corruption (or Election-related offense)', 'lat' => 14.089213, 'lng' => 121.148073],
            ['barangay' => 'POBLACION BARANGAY 4', 'road' => 'Poblacion 4 West Road', 'crime' => 'Child abuse / exploitation', 'lat' => 14.086750, 'lng' => 121.147788],

            // POBLACION BARANGAY 5
            ['barangay' => 'POBLACION BARANGAY 5', 'road' => 'Poblacion 5 Main Street', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.083611, 'lng' => 121.148349],
            ['barangay' => 'POBLACION BARANGAY 5', 'road' => 'Poblacion 5 Road 1', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.083279, 'lng' => 121.149573],
            ['barangay' => 'POBLACION BARANGAY 5', 'road' => 'Poblacion 5 Road 2', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.082883, 'lng' => 121.149056],
            ['barangay' => 'POBLACION BARANGAY 5', 'road' => 'Poblacion 5 Road 3', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.082637, 'lng' => 121.148809],
            ['barangay' => 'POBLACION BARANGAY 5', 'road' => 'Poblacion 5 Road 4', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.081841, 'lng' => 121.149308],
            ['barangay' => 'POBLACION BARANGAY 5', 'road' => 'Poblacion 5 Road 5', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.081442, 'lng' => 121.148915],
            ['barangay' => 'POBLACION BARANGAY 5', 'road' => 'Poblacion 5 Road 6', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.080988, 'lng' => 121.148604],

            // POBLACION BARANGAY 6
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 Main Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.088916, 'lng' => 121.143693],
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 Road 1', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.089895, 'lng' => 121.144879],
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 Central Street', 'crime' => 'Rape / Sexual assault', 'lat' => 14.088615, 'lng' => 121.145239],
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 Road 2', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.088423, 'lng' => 121.146036],
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 Road 3', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.087946, 'lng' => 121.145984],
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 Road 4', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.087485, 'lng' => 121.146797],
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 Road 5', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.086717, 'lng' => 121.146443],
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 East Avenue', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.086886, 'lng' => 121.145001],
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 West Road', 'crime' => 'Public scandal / Grave Infamy + Grave coercion (or Minor harassment)', 'lat' => 14.086083, 'lng' => 121.146155],
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 North Street', 'crime' => 'Drunk and disorderly conduct (or Public scandal / Grave scandal / Grave coercion)', 'lat' => 14.085698, 'lng' => 121.144116],
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 South Avenue', 'crime' => 'Physical injuries / Assault', 'lat' => 14.084821, 'lng' => 121.145599],
            ['barangay' => 'POBLACION BARANGAY 6', 'road' => 'Poblacion 6 Junction Road', 'crime' => 'FRUSTRATED Murder / Homicide', 'lat' => 14.084579, 'lng' => 121.144034],

            // POBLACION BARANGAY 7
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Main Street', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.080016, 'lng' => 121.147936],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 1', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.079721, 'lng' => 121.148236],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 2', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.079127, 'lng' => 121.148196],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 3', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.078122, 'lng' => 121.148198],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 4', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.077051, 'lng' => 121.148116],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 5', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.076358, 'lng' => 121.147008],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 6', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.076595, 'lng' => 121.148321],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 7', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.078267, 'lng' => 121.145757],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 8', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.078965, 'lng' => 121.144519],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 9', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.079329, 'lng' => 121.145508],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 10', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.079938, 'lng' => 121.145213],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 11', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.080467, 'lng' => 121.145869],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 Road 12', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.080726, 'lng' => 121.145089],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 East Street', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.081608, 'lng' => 121.146773],
            ['barangay' => 'POBLACION BARANGAY 7', 'road' => 'Poblacion 7 West Avenue', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.081757, 'lng' => 121.144550],

            // SAMBAT
            ['barangay' => 'SAMBAT', 'road' => 'Sambat Main Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.082414, 'lng' => 121.129139],
            ['barangay' => 'SAMBAT', 'road' => 'Sambat Road 1', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.083695, 'lng' => 121.129525],
            ['barangay' => 'SAMBAT', 'road' => 'Sambat Road 2', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.080978, 'lng' => 121.129106],
            ['barangay' => 'SAMBAT', 'road' => 'Sambat Road 3', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.080827, 'lng' => 121.132467],
            ['barangay' => 'SAMBAT', 'road' => 'Sambat Central Avenue', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.079485, 'lng' => 121.134275],
            ['barangay' => 'SAMBAT', 'road' => 'Sambat East Street', 'crime' => 'Illegal possession of firearms', 'lat' => 14.082062, 'lng' => 121.134117],
            ['barangay' => 'SAMBAT', 'road' => 'Sambat West Road', 'crime' => 'Public scandal / Grave Infamy + Grave coercion (or Minor harassment)', 'lat' => 14.083409, 'lng' => 121.133793],
            ['barangay' => 'SAMBAT', 'road' => 'Sambat North Avenue', 'crime' => 'Bribery / Corruption (or Election-related offense)', 'lat' => 14.084687, 'lng' => 121.134597],
            ['barangay' => 'SAMBAT', 'road' => 'Sambat South Street', 'crime' => 'Theft / Snatching', 'lat' => 14.083717, 'lng' => 121.136601],

            // SANTOR
            ['barangay' => 'SANTOR', 'road' => 'Santor Main Road', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.091798, 'lng' => 121.115946],
            ['barangay' => 'SANTOR', 'road' => 'Santor Central Street', 'crime' => 'Child abuse / exploitation', 'lat' => 14.091886, 'lng' => 121.107750],

            // SULPOC
            ['barangay' => 'SULPOC', 'road' => 'Sulpoc Main Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.139420, 'lng' => 121.072035],
            ['barangay' => 'SULPOC', 'road' => 'Sulpoc Central Avenue', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.137938, 'lng' => 121.067512],
            ['barangay' => 'SULPOC', 'road' => 'Sulpoc East Street', 'crime' => 'Bribery / Corruption (or Election-related offense)', 'lat' => 14.140326, 'lng' => 121.058512],

            // SUPLANG
            ['barangay' => 'SUPLANG', 'road' => 'Suplang Main Road', 'crime' => 'Child abuse / exploitation', 'lat' => 14.149075, 'lng' => 121.063368],

            // TALAGA
            ['barangay' => 'TALAGA', 'road' => 'Talaga Main Street', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.094427, 'lng' => 121.084452],
            ['barangay' => 'TALAGA', 'road' => 'Talaga Central Road', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.095848, 'lng' => 121.089539],
            ['barangay' => 'TALAGA', 'road' => 'Talaga East Avenue', 'crime' => 'Theft / Snatching', 'lat' => 14.088430, 'lng' => 121.077201],

            // TINURIK
            ['barangay' => 'TINURIK', 'road' => 'Tinurik Main Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.060380, 'lng' => 121.115434],
            ['barangay' => 'TINURIK', 'road' => 'Tinurik Road 1', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.060310, 'lng' => 121.118404],
            ['barangay' => 'TINURIK', 'road' => 'Tinurik Central Street', 'crime' => 'Rape / Sexual assault', 'lat' => 14.061575, 'lng' => 121.117295],
            ['barangay' => 'TINURIK', 'road' => 'Tinurik Road 2', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.063071, 'lng' => 121.118565],
            ['barangay' => 'TINURIK', 'road' => 'Tinurik Road 3', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.061276, 'lng' => 121.121506],
            ['barangay' => 'TINURIK', 'road' => 'Tinurik Road 4', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.062945, 'lng' => 121.120724],
            ['barangay' => 'TINURIK', 'road' => 'Tinurik Road 5', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.067493, 'lng' => 121.128165],
            ['barangay' => 'TINURIK', 'road' => 'Tinurik East Avenue', 'crime' => 'Illegal possession of firearms', 'lat' => 14.069621, 'lng' => 121.112809],
            ['barangay' => 'TINURIK', 'road' => 'Tinurik West Street', 'crime' => 'Bribery / Corruption (or Election-related offense)', 'lat' => 14.067665, 'lng' => 121.120683],
            ['barangay' => 'TINURIK', 'road' => 'Tinurik South Road', 'crime' => 'Drunk and disorderly conduct (or Public scandal / Grave scandal / Grave coercion)', 'lat' => 14.064906, 'lng' => 121.121577],

            // TRAPICHE
            ['barangay' => 'TRAPICHE', 'road' => 'Trapiche Main Road', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.097221, 'lng' => 121.126451],
            ['barangay' => 'TRAPICHE', 'road' => 'Trapiche Central Street', 'crime' => 'Illegal gambling (STL, jueteng)', 'lat' => 14.093167, 'lng' => 121.132790],
            ['barangay' => 'TRAPICHE', 'road' => 'Trapiche East Avenue', 'crime' => 'Illegal drugs possession (possession, trafficking, use)', 'lat' => 14.088342, 'lng' => 121.142117],
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
