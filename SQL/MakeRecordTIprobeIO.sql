DELIMITER $$
CREATE PROCEDURE RecordTIprobeIO(
	IN In_SubjectId TEXT,
	IN In_DateTime_Write DATETIME,
	IN In_ClientTimeZone TEXT,
	IN In_TIprobeIO TEXT
)
BEGIN
IF (SELECT COUNT(SubjectId) FROM TIprobeIO WHERE SubjectId=In_SubjectId)=0 THEN 
	INSERT INTO TIprobeIO (SubjectId, DateTime_Write, ClientTimeZone, TIprobeIO) VALUES (In_SubjectId, In_DateTime_Write, In_ClientTimeZone, In_TIprobeIO);
END IF;
END$$
DELIMITER ;