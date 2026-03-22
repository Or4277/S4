using System;
using System.Collections.Generic;
using System.Linq;
using Cairo;
using Data;
using Gtk;

public sealed class BoardWindow : Window
{
    private const int Padding = 20;
    private const double DotRadius = 2.5;
    private const double PlacedRadius = 7.0;
    private const double HitRadius = 10.0;

    private readonly int _rows;
    private readonly int _cols;
    private readonly DrawingArea _area;
    private readonly Button _endButton;
    private readonly Label _turnLabel;
    private readonly Label _leftScoreLabel;
    private readonly Label _rightScoreLabel;
    private readonly List<GameMove> _moves = new();
    private readonly List<GameLine> _lines = new();
    private readonly HashSet<string> _lineKeys = new();
    private readonly HashSet<string> _occupied = new();
    private readonly HashSet<string> _playerOne = new();
    private readonly HashSet<string> _playerTwo = new();
    private bool _isPlayerOne = true;

    public event EventHandler? TerminateRequested;

    public BoardWindow(int rows, int cols) : base($"Plateau {cols}x{rows}")
    {
        _rows = rows;
        _cols = cols;

        SetDefaultSize(700, 700);

        _area = new DrawingArea();
        _area.Drawn += OnDrawn;
        _area.AddEvents((int)Gdk.EventMask.ButtonPressMask);
        _area.ButtonPressEvent += OnBoardClick;
        _endButton = new Button("Terminer partie");
        _endButton.Clicked += OnEndButtonClicked;
        _turnLabel = new Label(GetTurnText()) { Xalign = 0 };
        _leftScoreLabel = new Label(GetLeftScoreText()) { Xalign = 0 };
        _rightScoreLabel = new Label(GetRightScoreText()) { Xalign = 1 };

        var boardRow = new HBox(false, 8);
        boardRow.PackStart(_leftScoreLabel, false, false, 0);
        boardRow.PackStart(_area, true, true, 0);
        boardRow.PackStart(_rightScoreLabel, false, false, 0);

        var root = new VBox(false, 6) { BorderWidth = 8 };
        root.PackStart(_turnLabel, false, false, 0);
        root.PackStart(boardRow, true, true, 0);
        root.PackStart(_endButton, false, false, 0);

        Add(root);
    }

    public string GetPlateauData()
    {
        var points = string.Join("|", _moves.Select(m => $"{m.X},{m.Y}"));
        return $"rows={_rows};cols={_cols};points={points}";
    }

    public IReadOnlyList<GameMove> GetMoves()
    {
        return _moves.AsReadOnly();
    }

    public IReadOnlyList<GameLine> GetLines()
    {
        return _lines.AsReadOnly();
    }

    public int GetLinesJ1Count()
    {
        return _lines.Count(l => l.Joueur == "J1");
    }

    public int GetLinesJ2Count()
    {
        return _lines.Count(l => l.Joueur == "J2");
    }

    private void OnEndButtonClicked(object? sender, EventArgs args)
    {
        TerminateRequested?.Invoke(this, EventArgs.Empty);
    }

    private void OnBoardClick(object? sender, ButtonPressEventArgs args)
    {
        if (!TryGetBoardGeometry(out var startX, out var startY, out var cellX, out var cellY))
        {
            return;
        }

        var clickX = args.Event.X;
        var clickY = args.Event.Y;

        var col = (int)Math.Round((clickX - startX) / cellX);
        var row = (int)Math.Round((clickY - startY) / cellY);

        if (row < 0 || row >= _rows || col < 0 || col >= _cols)
        {
            return;
        }

        var pointX = startX + col * cellX;
        var pointY = startY + row * cellY;

        var dx = clickX - pointX;
        var dy = clickY - pointY;
        if (Math.Sqrt(dx * dx + dy * dy) > HitRadius)
        {
            return;
        }

        var key = $"{col},{row}";
        if (_occupied.Contains(key))
        {
            return;
        }

        var joueur = _isPlayerOne ? "J1" : "J2";
        var tour = _moves.Count + 1;
        _moves.Add(new GameMove(col, row, joueur, tour));
        _occupied.Add(key);
        if (_isPlayerOne)
        {
            _playerOne.Add(key);
        }
        else
        {
            _playerTwo.Add(key);
        }

        DetectNewLines(col, row, joueur);

        _isPlayerOne = !_isPlayerOne;
        _turnLabel.Text = GetTurnText();
        _leftScoreLabel.Text = GetLeftScoreText();
        _rightScoreLabel.Text = GetRightScoreText();
        _area.QueueDraw();
    }

    private void OnDrawn(object? sender, DrawnArgs args)
    {
        var cr = args.Cr;
        var alloc = _area.Allocation;
        var width = alloc.Width;
        var height = alloc.Height;

        cr.SetSourceRGB(1, 1, 1);
        cr.Paint();

        if (!TryGetBoardGeometry(out var startX, out var startY, out var cellX, out var cellY))
        {
            return;
        }

        var boardWidth = cellX * (_cols - 1.0);
        var boardHeight = cellY * (_rows - 1.0);

        cr.SetSourceRGB(0.15, 0.15, 0.15);
        cr.LineWidth = 1.0;

        for (var r = 0; r < _rows; r++)
        {
            var y = startY + r * cellY;
            cr.MoveTo(startX, y);
            cr.LineTo(startX + boardWidth, y);
        }

        for (var c = 0; c < _cols; c++)
        {
            var x = startX + c * cellX;
            cr.MoveTo(x, startY);
            cr.LineTo(x, startY + boardHeight);
        }

        cr.Stroke();

        cr.SetSourceRGB(0.1, 0.1, 0.1);

        for (var r = 0; r < _rows; r++)
        {
            for (var c = 0; c < _cols; c++)
            {
                var x = startX + c * cellX;
                var y = startY + r * cellY;
                cr.Arc(x, y, DotRadius, 0, Math.PI * 2);
                cr.Fill();
            }
        }

        foreach (var move in _moves)
        {
            var x = startX + move.X * cellX;
            var y = startY + move.Y * cellY;
            if (move.Joueur == "J1")
            {
                cr.SetSourceRGB(0.85, 0.15, 0.15);
            }
            else
            {
                cr.SetSourceRGB(0.15, 0.35, 0.85);
            }

            cr.Arc(x, y, PlacedRadius, 0, Math.PI * 2);
            cr.Fill();
        }

        cr.LineWidth = 3.0;
        foreach (var line in _lines)
        {
            var points = ParsePoints(line.Points);
            if (points.Count < 2)
            {
                continue;
            }

            if (line.Joueur == "J1")
            {
                cr.SetSourceRGB(0.85, 0.15, 0.15);
            }
            else
            {
                cr.SetSourceRGB(0.15, 0.35, 0.85);
            }

            var first = points[0];
            var last = points[points.Count - 1];
            cr.MoveTo(startX + first.x * cellX, startY + first.y * cellY);
            cr.LineTo(startX + last.x * cellX, startY + last.y * cellY);
            cr.Stroke();
        }
    }

    private void DetectNewLines(int col, int row, string joueur)
    {
        var playerSet = joueur == "J1" ? _playerOne : _playerTwo;
        var directions = new (int dx, int dy)[]
        {
            (1, 0),
            (0, 1),
            (1, 1),
            (1, -1)
        };

        foreach (var (dx, dy) in directions)
        {
            var linePoints = GetLinePoints(col, row, dx, dy, playerSet);
            if (linePoints.Count != 5)
            {
                continue;
            }

            var key = string.Join("|", linePoints.Select(p => $"{p.x},{p.y}")
                .OrderBy(value => value, StringComparer.Ordinal));
            if (_lineKeys.Contains(key))
            {
                continue;
            }

            if (IsBlockedByOpponentLine(linePoints, joueur))
            {
                continue;
            }

            _lineKeys.Add(key);
            var pointsText = string.Join("|", linePoints.Select(p => $"{p.x},{p.y}"));
            _lines.Add(new GameLine(joueur, pointsText));
        }
    }

    private bool IsBlockedByOpponentLine(List<(int x, int y)> linePoints, string joueur)
    {
        if (linePoints.Count < 2)
        {
            return false;
        }

        var newStart = linePoints[0];
        var newEnd = linePoints[^1];

        foreach (var line in _lines)
        {
            if (line.Joueur == joueur)
            {
                continue;
            }

            var points = ParsePoints(line.Points);
            if (points.Count < 2)
            {
                continue;
            }

            var start = points[0];
            var end = points[^1];
            if (SegmentsProperlyIntersect(newStart, newEnd, start, end))
            {
                return true;
            }
        }

        return false;
    }

    private List<(int x, int y)> GetLinePoints(
        int col,
        int row,
        int dx,
        int dy,
        HashSet<string> playerSet)
    {
        var points = new List<(int x, int y)> { (col, row) };

        var x = col + dx;
        var y = row + dy;
        while (IsOwned(x, y, playerSet))
        {
            points.Add((x, y));
            x += dx;
            y += dy;
        }

        x = col - dx;
        y = row - dy;
        while (IsOwned(x, y, playerSet))
        {
            points.Insert(0, (x, y));
            x -= dx;
            y -= dy;
        }

        return points;
    }

    private bool IsOwned(int col, int row, HashSet<string> playerSet)
    {
        if (col < 0 || col >= _cols || row < 0 || row >= _rows)
        {
            return false;
        }

        return playerSet.Contains($"{col},{row}");
    }

    private bool TryGetBoardGeometry(out double startX, out double startY, out double cellX, out double cellY)
    {
        var alloc = _area.Allocation;
        var width = alloc.Width;
        var height = alloc.Height;
        var usableSize = Math.Min(width, height) - Padding * 2;

        if (usableSize <= 0 || _rows < 2 || _cols < 2)
        {
            startX = 0;
            startY = 0;
            cellX = 0;
            cellY = 0;
            return false;
        }

        cellX = usableSize / (_cols - 1.0);
        cellY = usableSize / (_rows - 1.0);

        var boardWidth = cellX * (_cols - 1.0);
        var boardHeight = cellY * (_rows - 1.0);

        startX = (width - boardWidth) / 2.0;
        startY = (height - boardHeight) / 2.0;

        return true;
    }

    private string GetTurnText()
    {
        return _isPlayerOne ? "Tour: J1 (rouge)" : "Tour: J2 (bleu)";
    }

    private string GetLeftScoreText()
    {
        return $"J1 (rouge)\nLignes: {GetLinesJ1Count()}";
    }

    private string GetRightScoreText()
    {
        return $"J2 (bleu)\nLignes: {GetLinesJ2Count()}";
    }

    private static List<(int x, int y)> ParsePoints(string points)
    {
        var result = new List<(int x, int y)>();
        if (string.IsNullOrWhiteSpace(points))
        {
            return result;
        }

        var parts = points.Split('|', StringSplitOptions.RemoveEmptyEntries);
        foreach (var part in parts)
        {
            var pair = part.Split(',');
            if (pair.Length != 2)
            {
                continue;
            }

            if (int.TryParse(pair[0], out var x) && int.TryParse(pair[1], out var y))
            {
                result.Add((x, y));
            }
        }

        return result;
    }

    private static bool SegmentsProperlyIntersect(
        (int x, int y) a,
        (int x, int y) b,
        (int x, int y) c,
        (int x, int y) d)
    {
        var o1 = Orientation(a, b, c);
        var o2 = Orientation(a, b, d);
        var o3 = Orientation(c, d, a);
        var o4 = Orientation(c, d, b);

        return o1 != o2 && o3 != o4;
    }

    private static int Orientation((int x, int y) a, (int x, int y) b, (int x, int y) c)
    {
        var value = (b.y - a.y) * (c.x - b.x) - (b.x - a.x) * (c.y - b.y);
        if (value == 0) return 0;
        return value > 0 ? 1 : 2;
    }

}
