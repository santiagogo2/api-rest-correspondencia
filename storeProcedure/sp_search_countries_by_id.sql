USE [SubredSur]
GO
/****** Object:  StoredProcedure [dbo].[sp_search_countries_by_id]    Script Date: 16/10/2019 4:50:18 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Santiago Ramírez Gaitán>
-- Create date: <16/10/2019>
-- Description:	<Procedimiento que retorna todos los elementos que se encuentran en la tabla country>
-- =============================================
CREATE PROCEDURE [dbo].[sp_search_countries_by_id] 
	-- Add the parameters for the stored procedure here
	@id 	int
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;

    -- Insert statements for procedure here
	SELECT * FROM [dbo].Country WHERE Country.id = @id
END
GO