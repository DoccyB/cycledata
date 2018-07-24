<?php

# Class to render blocks of HTML
class html
{
	public function makeTable ($data)
	{

	# Table headings
                $table  = "<table style='width:80%'>\n\t\t<tr>";

		foreach($data[0] as $tableHeading=>$value) {
			$table .= "\n\t\t\t<th>{$tableHeading}</th>";
		}
		$table .=  "\n\t\t</tr>";

		foreach($data as $row) {
		$table .= "\n\t\t<tr>";
                        # Values for table rows
                                foreach ($row as $value) {
                                        $table .= "\n\t\t\t<td>{$value}</td>";
                                }
                        $table .= "\n\t\t</tr>";
                }
                $table .= "\n\t</table>";
                return $table;
        }
}

?>
