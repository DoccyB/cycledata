<?php
class html
{
	public function makeTable ($data)
        {

                # Table headings
                $table  = "\t<table style='width:80%'>\n\t<tr>";

                foreach($data[0] as $tableHeading=>$value) {
                        $table .= "\n\t\t<th>{$tableHeading}</th>";
                }
                $table .=  "\n\t</tr>";

                foreach($data as $row) {
                        $table .= "\n\t<tr>";
                        # Values for table rows
                                foreach ($row as $value) {
                                        $table .= "\n\t\t<td>{$value}</td>";
                                }
                        $table .= "\n\t</tr>";
                }
                $table .= "\n\t</table>";
                return $table;
        }
}
?>
