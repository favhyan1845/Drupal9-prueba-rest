<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* core/themes/olivero/templates/views/views-view--frontpage.html.twig */
class __TwigTemplate_a6175a608ba72ef9b1f86c19d9ba9f2bf69b1b8b7fb02ce6d8f8b40b5ee8d2ae extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 35
        echo "
";
        // line 37
        $context["classes"] = [0 => "view", 1 => ("view-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 39
($context["id"] ?? null), 39, $this->source))), 2 => ("view-id-" . $this->sandbox->ensureToStringAllowed(        // line 40
($context["id"] ?? null), 40, $this->source)), 3 => ("view-display-id-" . $this->sandbox->ensureToStringAllowed(        // line 41
($context["display_id"] ?? null), 41, $this->source)), 4 => "grid-full", 5 => "layout--pass--content-narrow", 6 => "ie11-autorow"];
        // line 47
        echo "<div";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method", false, false, true, 47), 47, $this->source), "html", null, true);
        echo ">
  ";
        // line 48
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_prefix"] ?? null), 48, $this->source), "html", null, true);
        echo "
  ";
        // line 49
        if (($context["title"] ?? null)) {
            // line 50
            echo "    ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 50, $this->source), "html", null, true);
            echo "
  ";
        }
        // line 52
        echo "  ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_suffix"] ?? null), 52, $this->source), "html", null, true);
        echo "
  ";
        // line 53
        if (($context["header"] ?? null)) {
            // line 54
            echo "    <div class=\"view-header\">
      ";
            // line 55
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["header"] ?? null), 55, $this->source), "html", null, true);
            echo "
    </div>
  ";
        }
        // line 58
        echo "  ";
        if (($context["exposed"] ?? null)) {
            // line 59
            echo "    <div class=\"view-filters\">
      ";
            // line 60
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["exposed"] ?? null), 60, $this->source), "html", null, true);
            echo "
    </div>
  ";
        }
        // line 63
        echo "  ";
        if (($context["attachment_before"] ?? null)) {
            // line 64
            echo "    <div class=\"attachment attachment-before\">
      ";
            // line 65
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["attachment_before"] ?? null), 65, $this->source), "html", null, true);
            echo "
    </div>
  ";
        }
        // line 68
        echo "
  ";
        // line 69
        if (($context["rows"] ?? null)) {
            // line 70
            echo "    <div class=\"view-content\">
      ";
            // line 71
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["rows"] ?? null), 71, $this->source), "html", null, true);
            echo "
    </div>
  ";
        } elseif (        // line 73
($context["empty"] ?? null)) {
            // line 74
            echo "    ";
            $this->loadTemplate("@olivero/includes/get-started.html.twig", "core/themes/olivero/templates/views/views-view--frontpage.html.twig", 74)->display($context);
            // line 75
            echo "  ";
        }
        // line 76
        echo "
  ";
        // line 77
        if (($context["pager"] ?? null)) {
            // line 78
            echo "    ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["pager"] ?? null), 78, $this->source), "html", null, true);
            echo "
  ";
        }
        // line 80
        echo "  ";
        if (($context["attachment_after"] ?? null)) {
            // line 81
            echo "    <div class=\"attachment attachment-after\">
      ";
            // line 82
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["attachment_after"] ?? null), 82, $this->source), "html", null, true);
            echo "
    </div>
  ";
        }
        // line 85
        echo "  ";
        if (($context["more"] ?? null)) {
            // line 86
            echo "    ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["more"] ?? null), 86, $this->source), "html", null, true);
            echo "
  ";
        }
        // line 88
        echo "  ";
        if (($context["footer"] ?? null)) {
            // line 89
            echo "    <div class=\"view-footer\">
      ";
            // line 90
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer"] ?? null), 90, $this->source), "html", null, true);
            echo "
    </div>
  ";
        }
        // line 93
        echo "
</div>
";
    }

    public function getTemplateName()
    {
        return "core/themes/olivero/templates/views/views-view--frontpage.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  169 => 93,  163 => 90,  160 => 89,  157 => 88,  151 => 86,  148 => 85,  142 => 82,  139 => 81,  136 => 80,  130 => 78,  128 => 77,  125 => 76,  122 => 75,  119 => 74,  117 => 73,  112 => 71,  109 => 70,  107 => 69,  104 => 68,  98 => 65,  95 => 64,  92 => 63,  86 => 60,  83 => 59,  80 => 58,  74 => 55,  71 => 54,  69 => 53,  64 => 52,  58 => 50,  56 => 49,  52 => 48,  47 => 47,  45 => 41,  44 => 40,  43 => 39,  42 => 37,  39 => 35,);
    }

    public function getSourceContext()
    {
        return new Source("", "core/themes/olivero/templates/views/views-view--frontpage.html.twig", "C:\\wamp64\\www\\drupal\\prueba\\web\\core\\themes\\olivero\\templates\\views\\views-view--frontpage.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 37, "if" => 49, "include" => 74);
        static $filters = array("clean_class" => 39, "escape" => 47);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if', 'include'],
                ['clean_class', 'escape'],
                []
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
