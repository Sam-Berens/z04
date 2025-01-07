DELIMITER $$
CREATE PROCEDURE RecordTItrainIO(
	IN In_SubjectId TEXT,
	IN In_DateTime_Write DATETIME,
	IN In_ClientTimeZone TEXT,
	IN In_TItrainIO TEXT
)
BEGIN
IF (SELECT COUNT(SubjectId) FROM TItrainIO WHERE SubjectId=In_SubjectId)=0 THEN 
	INSERT INTO TItrainIO (SubjectId, DateTime_Write, ClientTimeZone, TItrainIO) VALUES (In_SubjectId, In_DateTime_Write, In_ClientTimeZone, In_TItrainIO);
END IF;
END$$
DELIMITER ;