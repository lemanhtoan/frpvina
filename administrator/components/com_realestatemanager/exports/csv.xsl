<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="text" encoding="utf-8"/>
	<xsl:template match="/">
		<xsl:for-each select="houses_data/houses_list/house">
			<xsl:value-of select="houseid"/><xsl:text>|</xsl:text>
			<xsl:value-of select="description"/><xsl:text>|</xsl:text>
			<xsl:value-of select="link"/><xsl:text>|</xsl:text>
			<xsl:value-of select="listing_type"/><xsl:text>|</xsl:text>
			<xsl:value-of select="price"/><xsl:text>|</xsl:text>
			<xsl:value-of select="priceunit"/><xsl:text>|</xsl:text>
			<xsl:value-of select="htitle"/><xsl:text>|</xsl:text>
			<xsl:value-of select="hcountry"/><xsl:text>|</xsl:text>
			<xsl:value-of select="hregion"/><xsl:text>|</xsl:text>
			<xsl:value-of select="hcity"/><xsl:text>|</xsl:text>
			<xsl:value-of select="hdistrict"/><xsl:text>|</xsl:text>
			<xsl:value-of select="hzipcode"/><xsl:text>|</xsl:text>
			<xsl:value-of select="hlocation"/><xsl:text>|</xsl:text>
			<xsl:value-of select="hlatitude"/><xsl:text>|</xsl:text>
			<xsl:value-of select="hlongitude"/><xsl:text>|</xsl:text>
			<xsl:value-of select="map_zoom"/><xsl:text>|</xsl:text>
			<xsl:value-of select="bathrooms"/><xsl:text>|</xsl:text>
			<xsl:value-of select="bedrooms"/><xsl:text>|</xsl:text>
			<xsl:value-of select="broker"/><xsl:text>|</xsl:text>
			<xsl:value-of select="contacts"/><xsl:text>|</xsl:text>
			<xsl:value-of select="image_link"/><xsl:text>|</xsl:text>
			<xsl:value-of select="listing_status"/><xsl:text>|</xsl:text>			
			<xsl:value-of select="price_type"/><xsl:text>|</xsl:text>
			<xsl:value-of select="property_type"/><xsl:text>|</xsl:text>
			<xsl:value-of select="provider_class"/><xsl:text>|</xsl:text>
			<xsl:value-of select="year"/><xsl:text>|</xsl:text>
			<xsl:value-of select="agent"/><xsl:text>|</xsl:text>
			<xsl:value-of select="area"/><xsl:text>|</xsl:text>
			<xsl:value-of select="expiration_date"/><xsl:text>|</xsl:text>
			<xsl:value-of select="feature"/><xsl:text>|</xsl:text>
			<xsl:value-of select="hoa_dues"/><xsl:text>|</xsl:text>
			<xsl:value-of select="lot_size"/><xsl:text>|</xsl:text>
			<xsl:value-of select="model"/><xsl:text>|</xsl:text>
			<xsl:value-of select="property_taxes"/><xsl:text>|</xsl:text>
			<xsl:value-of select="school"/><xsl:text>|</xsl:text>
			<xsl:value-of select="school_district"/><xsl:text>|</xsl:text>
			<xsl:value-of select="style"/><xsl:text>|</xsl:text>
			<xsl:value-of select="zoning"/><xsl:text>|</xsl:text>
			<xsl:value-of select="date"/><xsl:text>|</xsl:text>
			<xsl:value-of select="hits"/><xsl:text>|</xsl:text>
			<xsl:value-of select="published"/><xsl:text>|</xsl:text>
			<xsl:value-of select="owneremail"/><xsl:text>|</xsl:text>
            <xsl:value-of select="featured_clicks"/><xsl:text>|</xsl:text>
            <xsl:value-of select="featured_shows"/><xsl:text>|</xsl:text>
			<xsl:value-of select="extra1"/><xsl:text>|</xsl:text>
			<xsl:value-of select="extra2"/><xsl:text>|</xsl:text>
            <xsl:value-of select="extra3"/><xsl:text>|</xsl:text>
            <xsl:value-of select="extra4"/><xsl:text>|</xsl:text>
            <xsl:value-of select="extra5"/><xsl:text>|</xsl:text>
            <xsl:value-of select="extra6"/><xsl:text>|</xsl:text>
            <xsl:value-of select="extra7"/><xsl:text>|</xsl:text>
            <xsl:value-of select="extra8"/><xsl:text>|</xsl:text>
            <xsl:value-of select="extra9"/><xsl:text>|</xsl:text>
            <xsl:value-of select="extra10"/><xsl:text>|</xsl:text>
            <xsl:value-of select="language"/><xsl:text>|</xsl:text>
            <xsl:value-of select="owner_id"/><xsl:text> &#xA;</xsl:text>

		</xsl:for-each>
	</xsl:template>
</xsl:stylesheet>

