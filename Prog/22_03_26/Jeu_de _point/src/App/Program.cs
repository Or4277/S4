using Data;
using Gtk;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.DependencyInjection;

Application.Init();

var config = new ConfigurationBuilder()
    .SetBasePath(AppContext.BaseDirectory)
    .AddJsonFile("appsettings.json", optional: false, reloadOnChange: false)
    .Build();

var services = new ServiceCollection();
services.Configure<DatabaseOptions>(config.GetSection(DatabaseOptions.SectionName));
services.AddSingleton<IDbConnectionFactory, NpgsqlConnectionFactory>();
services.AddTransient<IGameRepository, GameRepository>();
var provider = services.BuildServiceProvider();

var repository = provider.GetRequiredService<IGameRepository>();

var (rows, cols) = ShowBoardSizeDialog();
if (rows < 2 || cols < 2)
{
    return;
}

ShowBoardWindow(rows, cols, repository);
Application.Run();

static void ShowBoardWindow(int rows, int cols, IGameRepository repository)
{
    var window = new BoardWindow(rows, cols);
    window.DeleteEvent += (_, __) => Application.Quit();

    window.TerminateRequested += async (_, __) =>
    {
        var plateau = window.GetPlateauData();
        var moves = window.GetMoves();
        var lines = window.GetLines();
        var lignesJ1 = window.GetLinesJ1Count();
        var lignesJ2 = window.GetLinesJ2Count();
        await repository.SaveGameAsync(plateau, moves, lines, lignesJ1, lignesJ2);

        window.Destroy();

        var (newRows, newCols) = ShowBoardSizeDialog();
        if (newRows < 2 || newCols < 2)
        {
            Application.Quit();
            return;
        }

        ShowBoardWindow(newRows, newCols, repository);
    };

    window.ShowAll();
}

static (int rows, int cols) ShowBoardSizeDialog()
{
    var dialog = new Dialog("Taille du plateau", null, DialogFlags.Modal);
    dialog.AddButton("Annuler", ResponseType.Cancel);
    dialog.AddButton("OK", ResponseType.Ok);
    dialog.SetDefaultSize(300, 140);

    var grid = new Grid
    {
        ColumnSpacing = 8,
        RowSpacing = 8,
        Margin = 12
    };

    var rowsLabel = new Label("Hauteur") { Xalign = 0 };
    var colsLabel = new Label("Largeur") { Xalign = 0 };

    var rowsSpin = new SpinButton(2, 50, 1) { Value = 9 };
    var colsSpin = new SpinButton(2, 50, 1) { Value = 9 };

    grid.Attach(rowsLabel, 0, 0, 1, 1);
    grid.Attach(rowsSpin, 1, 0, 1, 1);
    grid.Attach(colsLabel, 0, 1, 1, 1);
    grid.Attach(colsSpin, 1, 1, 1, 1);

    dialog.ContentArea.Add(grid);
    dialog.ShowAll();

    var response = (ResponseType)dialog.Run();
    var rows = (int)rowsSpin.Value;
    var cols = (int)colsSpin.Value;
    dialog.Destroy();

    return response == ResponseType.Ok ? (rows, cols) : (0, 0);
}
