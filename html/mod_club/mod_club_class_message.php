<?php
	class Message
	{
		public static function showErrorMessage($message)
		{
			self::showMessage($message, "error_message", "Erreur !");
		}
		
		public static function showInformationMessage($message)
		{
			self::showMessage($message, "info_message", "Information !");
		}
		
		public static function showSuccessMessage($message)
		{
			self::showMessage($message, "success_message", "Succès !");
		}
		
		private static function showMessage($message, $type, $message_type)
		{
			echo"<div class=\"".$type."\"><span class=\"span_".$type."\">".$message_type."</span><span>".$message."</span></div>";
		}
	}
?>