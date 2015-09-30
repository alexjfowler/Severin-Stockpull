CREATE TABLE stocks (
	symbol VARCHAR(30) NOT NULL,
    snapshotTime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    latestValue DECIMAL(11,3),
	open DECIMAL(11,3),
	latestClose DECIMAL(11,3),
	PRIMARY KEY(symbol, snapshotTime)
)
