<?xml version="1.0" ?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:template match="module">
        <div class="module">
            <h3>Module: <xsl:value-of select="@name" /></h3>
            <div>
                <h4>Decorators</h4>
                <blockquote>
                    <xsl:apply-templates select="./decorator">
                        <xsl:with-param name="module" select="@name" />
                    </xsl:apply-templates>
                </blockquote>
                <h4>Fields</h4>
                <blockquote>
                    <xsl:apply-templates select="./field">
                        <xsl:with-param name="module" select="@name" />
                    </xsl:apply-templates>
                </blockquote>
            </div>
            <input type="submit" name="form[action][module][@module][save]" value="Save" />
        </div>
    </xsl:template>
    
    
    
    <xsl:template match="field">
        <div>
            <h4><xsl:value-of select="@name" />: <xsl:value-of select="@display" /> (<xsl:value-of select="@type" />)</h4>
            <blockquote>
                <xsl:apply-templates select="./decorator"  />
            </blockquote>
        </div>
    </xsl:template>


    <xsl:template match="decorator">
        <xsl:param name="module" />
        <div>
            <h4><xsl:value-of select="@name" /></h4>
        </div>
    </xsl:template>


    <xsl:template match="decorator[@name='unique']">
        <xsl:param name="module" />
        <div>
            <xsl:value-of select="@name" />
        </div>
    </xsl:template>


    <xsl:template match="decorator[@name='minlength']">
        <xsl:param name="module" />
        <div>
            <xsl:value-of select="@name" />: <xsl:value-of select="@value" />
        </div>
    </xsl:template>


</xsl:stylesheet>
