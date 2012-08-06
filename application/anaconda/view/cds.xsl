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
            <input type="text" name="form[field][module][{@name}][createField][name]" value="" placeholder="name" />
            <input type="text" name="form[field][module][{@name}][createField][type]" value="" placeholder="type" />
            <input type="text" name="form[field][module][{@name}][createField][display]" value="" placeholder="display" />
            <input type="submit" name="form[action][module][{@name}][createField]" value="Save" />
        </div>
    </xsl:template>
    
    
    
    <xsl:template match="field">
        <xsl:param name="module" />
        <h4><xsl:value-of select="@name" />: <xsl:value-of select="@display" /> (<xsl:value-of select="@type" />)</h4>
        <div>
            <blockquote>
                <xsl:apply-templates select="./decorator">
                    <xsl:with-param name="module" select="$module" />
                    <xsl:with-param name="field" select="@name" />
                </xsl:apply-templates>
            </blockquote>
        </div>
    </xsl:template>


    <xsl:template match="decorator">
        <xsl:param name="module" />
        <h4><xsl:value-of select="@name" /></h4>
        <div>
        </div>
    </xsl:template>


    <xsl:template match="decorator[@type='unique']">
        <xsl:param name="module" />
        <div>
            <xsl:value-of select="@type" />
            <input type="text" name="form[field][module][{$module}][{@type}]" value="{@type}" />
        </div>
    </xsl:template>


    <xsl:template match="decorator[@type='minlength']">
        <xsl:param name="module" />
        <div>
            <xsl:value-of select="@type" />:
            <input type="text" name="form[field][module][{$module}][name][unique]" value="{@value}" />
        </div>
    </xsl:template>


</xsl:stylesheet>
